<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // 商品一覧の表示
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');
            $companyId = $request->input('company');
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');
            $minStock = $request->input('min_stock');
            $maxStock = $request->input('max_stock');

            $query = Product::query();

            if ($search) {
                $query->where('product_name', 'like', '%' . $search . '%');
            }

            if ($companyId) {
                $query->where('company_id', $companyId);
            }

            if ($minPrice || $maxPrice) {
                $query->whereBetween('price', [$minPrice ?? 0, $maxPrice ?? PHP_INT_MAX]);
            }

            if ($minStock || $maxStock) {
                $query->whereBetween('stock', [$minStock ?? 0, $maxStock ?? PHP_INT_MAX]);
            }

            $products = $query->orderBy('id', 'desc')->get();
            $companies = Company::all();

            return view('products.index', compact('products', 'search', 'companies', 'companyId', 'minPrice', 'maxPrice', 'minStock', 'maxStock'));
        } catch (\Exception $e) {
            Log::error('商品一覧の表示中にエラーが発生しました: ' . $e->getMessage());
            return back()->withErrors(['error' => '商品一覧の表示中にエラーが発生しました。']);
        }
    }

    // 商品作成フォームの表示
    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    // 商品の保存
    public function store(Request $request)
    {
        Log::info('Request Data (Store):', $request->all());

        // 入力バリデーション
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'company_id' => 'required|exists:companies,id',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|file|image|max:2048',
        ]);

        try {
            $productData = $request->only(['product_name', 'price', 'stock', 'company_id', 'comment']);

            // 画像処理
            if ($request->hasFile('img_path')) {
                $imagePath = $request->file('img_path')->store('images', 'public');
                $productData['img_path'] = $imagePath;
            }

            Log::info('Processed Product Data:', $productData);

            // 商品の保存
            Product::create($productData);

            return redirect()->route('products.index')->with('success', '商品が追加されました。');
        } catch (\Exception $e) {
            Log::error('商品登録中にエラーが発生しました: ' . $e->getMessage());
            return back()->withErrors(['error' => '商品登録中にエラーが発生しました。']);
        }
    }

    // 商品詳細の表示
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            Log::error('商品詳細の表示中にエラーが発生しました: ' . $e->getMessage());
            return back()->withErrors(['error' => '商品詳細の表示中にエラーが発生しました。']);
        }
    }

    // 商品編集フォームの表示
    public function edit($id)
    {
        try {
            $product = Product::findOrFail($id);
            $companies = Company::all();
            return view('products.edit', compact('product', 'companies'));
        } catch (\Exception $e) {
            Log::error('商品編集フォームの表示中にエラーが発生しました: ' . $e->getMessage());
            return back()->withErrors(['error' => '商品編集フォームの表示中にエラーが発生しました。']);
        }
    }

    // 商品の更新
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'company_id' => 'required|exists:companies,id',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|file|image|max:2048',
        ]);

        try {
            $product = Product::findOrFail($id);
            $productData = $request->only(['product_name', 'price', 'stock', 'company_id', 'comment']);

            Log::info('Before saving product:', $productData);

            // 画像がアップロードされている場合の処理
            if ($request->hasFile('img_path')) {
                $imagePath = $request->file('img_path')->store('images', 'public');
                $productData['img_path'] = $imagePath;
            }

            Log::info('Processed Product Data:', $productData);

            // 商品の更新
            $product->update($productData);

            return redirect()->route('products.index')->with('success', '商品が更新されました。');
        } catch (\Exception $e) {
            Log::error('商品更新中にエラーが発生しました: ' . $e->getMessage());
            return back()->withErrors(['error' => '商品更新中にエラーが発生しました。']);
        }
    }

    // 商品の削除
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('商品削除中にエラーが発生しました: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => '削除に失敗しました。']);
        }
    }

    // 商品検索の処理
    public function search(Request $request)
    {
        try {
            $searchQuery = $request->input('query');
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');
            $minStock = $request->input('min_stock');
            $maxStock = $request->input('max_stock');

            $queryBuilder = Product::query();

            if ($searchQuery) {
                $queryBuilder->where('product_name', 'LIKE', "%{$searchQuery}%");
            }

            if ($minPrice || $maxPrice) {
                $queryBuilder->whereBetween('price', [$minPrice ?? 0, $maxPrice ?? PHP_INT_MAX]);
            }

            if ($minStock || $maxStock) {
                $queryBuilder->whereBetween('stock', [$minStock ?? 0, $maxStock ?? PHP_INT_MAX]);
            }

            $products = $queryBuilder->get();
            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('商品検索中にエラーが発生しました: ' . $e->getMessage());
            return response()->json(['error' => '商品検索中にエラーが発生しました。'], 500);
        }
    }
}
