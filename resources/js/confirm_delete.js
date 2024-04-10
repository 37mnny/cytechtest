// public/js/confirm_delete.js

function confirmDelete(id) {
    if (confirm('本当に削除しますか？')) {
        // OKボタンがクリックされた場合、削除処理の実行
        document.getElementById('delete-form-' + id).submit();
    }
}
