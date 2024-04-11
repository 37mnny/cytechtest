<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $companyId = $request->input('company');

    // 商品情報を取得
    $query = Product::query();

    // 検索キーワードがある場合、商品名で部分一致検索を行う
    if ($search) {
        $query->where('product_name', 'like', '%' . $search . '%');

    }

    // 企業名で絞り込み
    if ($companyId) {
        $query->where('company_id', $companyId);
    }

    $products = $query->get();
    $companies = Company::all();

    // ビューを返す
    // コントローラーの index メソッド内
return view('products.index', compact('products', 'search', 'companies', 'companyId'));

}



public function create()
{
    // 全てのメーカー情報を取得
    $companies = Company::all();

    // 新規登録用のビューを表示
    return view('products.create', compact('companies'));
}

public function destroy($id)
{
    // 商品を取得
    $product = Product::findOrFail($id);

    // 商品を削除
    $product->delete();

    // 削除後、商品一覧ページにリダイレクト
    return redirect()->route('products.index')->with('success', '商品が削除されました');
}

        // モデルインスタンスである$productに対して行われた変更をデータベースに保存するためのメソッド（機能）です。

        // 全ての処理が終わったら、商品一覧画面に戻ります。
        public function store(Request $request)
{
    // バリデーションなどの処理をここに追加

    // フォームから送信されたデータを使って新しい商品を作成する
    $product = new Product();
    $product->product_name = $request->input('product_name');
    $product->company_id = $request->input('company_id');
    $product->price = $request->input('price');
    $product->stock = $request->input('stock');
    $product->comment = $request->input('comment');

    // 画像のアップロード処理
    if ($request->hasFile('img_path')) {
        $imgPath = $request->file('img_path')->store('images');
        $product->img_path = $imgPath;
    }

    // 保存処理
    $product->save();

    // 新規登録が完了したらリダイレクトするなどの処理を行う
    return redirect()->route('products.create')->with('success', '商品が登録されました');

    
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

    // 画像のアップロード処理
    if ($request->hasFile('img_path')) {
        $imgPath = $request->file('img_path')->store('images');
        $product->img_path = $imgPath;
    }

    $product->save();

    return redirect()->route('products.index')->with('success', '商品情報が更新されました');
}





    }


