@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-header">
                <h2>商品情報編集画面</h2>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="product_name" class="form-label required">商品名</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="company_id" class="form-label required">メーカー</label>
                        <select class="form-select" id="company_id" name="company_id">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ $product->company_id == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label required">金額</label>
                        <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label required">在庫数</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="comment" class="form-label">コメント</label>
                        <textarea id="comment" name="comment" class="form-control" rows="3">{{ $product->comment }}</textarea>
                    </div>

                    <div class="mb-3">
    <label for="img_path" class="form-label">商品画像:</label>
    <input id="img_path" type="file" name="img_path" class="form-control">
    @if($product->img_path)
        <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" class="product-image">
    @endif
</div>


                    <div class="row mb-3">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">更新</button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-secondary">戻る</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
