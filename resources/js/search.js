$(document).ready(function() {
    $('#search-form').on('submit', function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: "{{ route('products.index') }}", // BladeテンプレートのURLを直接指定
            type: 'GET',
            data: formData,
            success: function(response) {
                var tbody = $('tbody');
                tbody.empty();

                $.each(response, function(index, product) {
                    var row = '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + product.image + '</td>' +
                            '<td>' + product.product_name + '</td>' +
                            '<td>' + product.price + '</td>' +
                            '<td>' + product.stock + '</td>' +
                            '<td>' + (product.company ? product.company.company_name : '') + '</td>' +
                            '<td>' +
                            '<a href="/products/' + product.id + '" class="btn btn-info btn-sm mx-1">詳細表示</a>' +
                            '<form method="POST" action="/products/' + product.id + '" class="d-inline" onsubmit="return confirm(\'本当に削除しますか？\');">' +
                            '<input type="hidden" name="_token" value="' + $('meta[name="csrf-token"]').attr('content') + '">' +
                            '<input type="hidden" name="_method" value="DELETE">' +
                            '<button type="submit" class="btn btn-danger btn-sm mx-1">削除</button>' +
                            '</form>' +
                            '</td>' +
                            '</tr>';
                    tbody.append(row);
                });
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });
});
