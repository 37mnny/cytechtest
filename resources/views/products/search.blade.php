<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Search</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form id="search-form">
        <input type="text" id="search-query" name="search" placeholder="Search for products...">
        <br>
        <label for="min-price">Min Price:</label>
        <input type="number" id="min-price" name="min_price">
        <label for="max-price">Max Price:</label>
        <input type="number" id="max-price" name="max_price">
        <br>
        <label for="min-stock">Min Stock:</label>
        <input type="number" id="min-stock" name="min_stock">
        <label for="max-stock">Max Stock:</label>
        <input type="number" id="max-stock" name="max_stock">
        <br>
        <button type="button" id="search-button">Search</button>
    </form>

    <div id="search-results">
        <!-- 検索結果がここに表示されます -->
    </div>

    <script>
        // LaravelのルートをJavaScriptに渡す
        var searchUrl = "{{ route('products.search.results') }}";

        $(document).ready(function() {
            $('#search-button').on('click', function() {
                var formData = $('#search-form').serialize();

                $.ajax({
                    url: searchUrl,  // JavaScript変数を使用
                    type: 'GET',
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        var resultsDiv = $('#search-results');
                        resultsDiv.empty();

                        data.forEach(function(product) {
                            resultsDiv.append('<div>' + product.product_name + '</div>');
                        });
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>
