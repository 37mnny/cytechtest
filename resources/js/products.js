$(document).ready(function() {
    // 非同期削除処理
    $('.delete-form').on('submit', function(e) {
        e.preventDefault(); // デフォルトのフォーム送信を防ぐ

        if (confirm('この商品を削除してもよろしいですか？')) {
            let form = $(this);
            let productId = form.data('product-id');
            let token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: {
                    _token: token,
                    _method: 'DELETE'
                },
                success: function(response) {
                    if (response.success) {
                        $(`#product-${productId}`).remove(); // 成功した場合、該当の行を削除
                        alert('商品が削除されました。');
                    } else {
                        alert(response.error || '削除中にエラーが発生しました。');
                    }
                },
                error: function(xhr) {
                    console.error('Error deleting product:', xhr);
                    alert(xhr.responseJSON?.error || '削除中にエラーが発生しました。');
                }
            });
        } else {
            alert('削除がキャンセルされました。');
        }
    });
});
