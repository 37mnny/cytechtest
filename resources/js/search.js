$(document).ready(function() {
    $('#search-button').on('click', function() {
        var formData = $('#search-form').serialize();

        $.ajax({
            url: '{{ route("products.search") }}', // 修正: 正しいルートを設定
            type: 'GET',
            data: formData,
            dataType: 'json',
            success: function(data) {
                var resultsTable = $('#product-table tbody');
                resultsTable.empty();

                if (data.length > 0) {
                    data.forEach(function(product, index) {
                        var newRow = '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td><img src="/storage/' + product.img_path + '" width="100"></td>' +
                            '<td>' + product.product_name + '</td>' +
                            '<td>' + product.price + '</td>' +
                            '<td>' + product.stock + '</td>' +
                            '<td>' + product.company_name + '</td>' +
                            '<td><a href="/products/' + product.id + '" class="btn btn-info btn-sm mx-1">詳細表示</a></td>' +
                            '</tr>';
                        resultsTable.append(newRow);
                    });
                } else {
                    resultsTable.append('<tr><td colspan="7">検索結果がありません。</td></tr>');
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });
});
