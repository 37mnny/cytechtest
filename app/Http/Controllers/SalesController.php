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

        
        $product = Product::find($validated['product_id']);

  
        if (!$product) {
            return response()->json(['error' => '商品が見つかりません。'], 404);
        }

        // 在庫の確認
        if ($product->stock < $validated['quantity']) {
            return response()->json(['error' => '在庫が不足しています。'], 400);
        }

        try {
            DB::beginTransaction();

            
            $product->stock -= $validated['quantity'];
            $product->save();

           
            Sale::create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'total_price' => $product->price * $validated['quantity'],
            ]);

            DB::commit();

            
            return response()->json(['message' => '購入が完了しました。'], 200)
                    ->header('Content-Type', 'application/json; charset=UTF-8');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '購入処理中にエラーが発生しました。'], 500);
        }
    }
}
