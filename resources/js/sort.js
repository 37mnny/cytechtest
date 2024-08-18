$(document).ready(function() {
    // テーブルをtablesorterで初期化
    $('.tablesorter').tablesorter();

    // 初期表示時のソート設定（ID列で降順）
    $('.tablesorter').trigger('sorton', [[[0,1]]]);
});
