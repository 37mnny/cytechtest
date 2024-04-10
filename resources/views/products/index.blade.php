@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧画面</title>
</head>
<body>
    <h1>商品一覧画面</h1>
    <!-- 検索フォーム -->
    <form action="{{ route('products.index') }}" method="GET">
        <input type="text" name="search" placeholder="検索キーワード" value="{{ $search }}">
        <select name="company">
            <option value="">メーカーを選択</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ $companyId == $company->id ? 'selected' : '' }}>
                    {{ $company->company_name }}
                </option>
            @endforeach
        </select>
        <button type="submit">検索</button>
        <a href="{{ route('products.create') }}" class="btn btn-info btn-sm mx-1">新規登録</a>
    </form>

    <!-- 商品一覧 -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>メーカー名</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->image }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ optional($product->company)->company_name }}</td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細表示</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mx-1">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
@endsection
