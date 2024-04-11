@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品新規登録画面</h1>

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="product_name" class="form-label required">商品名</label>
            <input id="product_name" type="text" name="product_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="company_id" class="form-label required">メーカー名</label>
            <select class="form-select" id="company_id" name="company_id" required>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label required">価格</label>
            <input id="price" type="text" name="price" class="form-control" pattern="[0-9]+" title="半角数字で入力してください" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label required">在庫数</label>
            <input id="stock" type="text" name="stock" class="form-control" pattern="[0-9]+" title="半角数字で入力してください" required>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">コメント</label>
            <textarea id="comment" name="comment" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="img_path" class="form-label">商品画像</label>
            <input id="img_path" type="file" name="img_path" class="form-control">
        </div>
        
        <div class="mb-3 d-flex justify-content-between">
            <a href="{{ route('products.index') }}" class="btn btn-primary">戻る</a>
            <button type="submit" class="btn btn-primary">登録</button>
        </div>
    </form>
</div>
@endsection
