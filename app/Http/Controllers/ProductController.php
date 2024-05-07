<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');
            $companyId = $request->input('company');

            $query = Product::query();

            if ($search) {
                $query->where('product_name', 'like', '%' . $search . '%');
            }

            if ($companyId) {
                $query->where('company_id', $companyId);
            }

            $products = $query->get();
            $companies = Company::all();

            return view('products.index', compact('products', 'search', 'companies', 'companyId'));
        } catch (\Exception $e) {
            // エラーが発生した場合の処理
            // 例えば、ログを記録したり、エラーメッセージを表示したりします
            return back()->withErrors(['error' => 'エラーが発生しました。']);
        }
    }
    public function create()
{
    $companies = Company::all();
    return view('products.create', compact('companies'));
}


    public function store(Request $request)
    {
        try {
            $product = new Product();
            $product->product_name = $request->input('product_name');
            $product->company_id = $request->input('company_id');
            $product->price = $request->input('price');
            $product->stock = $request->input('stock');
            $product->comment = $request->input('comment');

            if ($request->hasFile('img_path')) {
                $imgPath = $request->file('img_path')->store('public/images');
                // 'public/' を削除してファイルパスを修正する
                $imgPath = str_replace('public/', '', $imgPath);
                $product->img_path = $imgPath;
            }

            $product->save();

            return redirect()->route('products.create')->with('success', '商品が登録されました');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'エラーが発生しました。もう一度やり直してください。']);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return redirect()->route('products.index')->with('success', '商品が削除されました');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'エラーが発生しました。もう一度やり直してください。']);
        }
    }
    
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'エラーが発生しました。']);
        }
    }
    
    public function edit($id)
    {
        try {
            $product = Product::findOrFail($id);
            $companies = Company::all();
            return view('products.edit', compact('product', 'companies'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'エラーが発生しました。']);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->product_name = $request->input('product_name');
            $product->company_id = $request->input('company_id');
            $product->price = $request->input('price');
            $product->stock = $request->input('stock');
            $product->comment = $request->input('comment');
    
            if ($request->hasFile('img_path')) {
                $imgPath = $request->file('img_path')->store('public/images');
                $product->img_path = str_replace('public/', '', $imgPath);
            }
            
            
            $product->save();
    
            return redirect()->route('products.index')->with('success', '商品情報が更新されました');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'エラーが発生しました。もう一度やり直してください。']);
        }
    }
    
}

