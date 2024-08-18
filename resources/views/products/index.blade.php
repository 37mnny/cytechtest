@extends('layouts.app')

@section('content')
    <h1>商品一覧画面</h1>
    <!-- 検索フォーム -->
    <form action="{{ route('products.index') }}" method="GET" id="search-form">
        <input type="text" name="search" placeholder="検索キーワード" value="{{ $search }}">
        
        <select name="company">
            <option value="">メーカーを選択</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ $companyId == $company->id ? 'selected' : '' }}>
                    {{ $company->company_name }}
                </option>
            @endforeach
        </select>
        
        <label for="min_price">価格(下限):</label>
        <input type="number" name="min_price" id="min_price" value="{{ $minPrice }}">
        
        <label for="max_price">価格(上限):</label>
        <input type="number" name="max_price" id="max_price" value="{{ $maxPrice }}">
        
        <label for="min_stock">在庫数(下限):</label>
        <input type="number" name="min_stock" id="min_stock" value="{{ $minStock }}">
        
        <label for="max_stock">在庫数(上限):</label>
        <input type="number" name="max_stock" id="max_stock" value="{{ $maxStock }}">
        
        <button type="submit">検索</button>
        <a href="{{ route('products.create') }}" class="btn btn-info btn-sm mx-1">新規登録</a>
    </form>

    <!-- 商品一覧 -->
    <table id="product-table" class="tablesorter">
        <thead>
            <tr>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>メーカー名</th>
                <th>操作</th>
                <th>購入</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
                <tr id="product-{{ $product->id }}">
                    <td>{{ $index + 1 }}</td>
                    <td><img src="{{ asset('storage/' . $product->img_path) }}" width="100"></td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ optional($product->company)->company_name }}</td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細表示</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline delete-form" data-product-id="{{ $product->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mx-1">削除</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('purchase') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary">購入</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
    <script src="{{ asset('js/products.js') }}"></script>
@endsection
