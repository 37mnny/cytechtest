<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Sale;


class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // 商品の取得
            $product = Product::find($validated['product_id']);

            if (!$product) {
                DB::rollBack();
                return response()->json(['error' => '商品が見つかりません。'], 404);
            }

            // 在庫の確認
            if ($product->stock < $validated['quantity']) {
                DB::rollBack();
                return response()->json(['error' => '在庫が不足しています。'], 400);
            }

            // 在庫の減少
            $product->stock -= $validated['quantity'];
            $product->save();

            // 売上の記録
            Sale::create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'total_price' => $product->price * $validated['quantity'],
            ]);

            DB::commit();

            // 成功メッセージを返す
            return response()->json(['message' => '購入が完了しました。'], 200)
                     ->header('Content-Type', 'application/json; charset=UTF-8');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '購入処理中にエラーが発生しました。'], 500);
        }
    }
}
