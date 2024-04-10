<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
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
    }

    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', '商品が削除されました');
    }

    public function store(Request $request)
    {
        $rules = [
            'product_name' => 'required|string',
            'company_id' => 'required|exists:companies,id',
            'price' => ['required', 'numeric', 'regex:/^[0-9]+$/u'],
            'stock' => ['required', 'numeric', 'regex:/^[0-9]+$/u'],
            'comment' => 'required|string',
            'img_path' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $messages = [
            'price.regex' => '価格は半角数字で入力してください。',
            'stock.regex' => '在庫数は半角数字で入力してください。',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product = new Product();
        $product->product_name = $request->input('product_name');
        $product->company_id = $request->input('company_id');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->comment = $request->input('comment');

        if ($request->hasFile('img_path')) {
            $imgPath = $request->file('img_path')->store('images');
            $product->img_path = $imgPath;
        }

        $product->save();

        return redirect()->route('products.index')->with('success', '商品が登録されました');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->product_name = $request->input('product_name');
        $product->company_id = $request->input('company_id');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->comment = $request->input('comment');

        if ($request->hasFile('img_path')) {
            $imgPath = $request->file('img_path')->store('images');
            $product->img_path = $imgPath;
        }

        $product->save();

        return redirect()->route('products.show', $product->id)->with('success', '商品情報が更新されました');
    }
}
