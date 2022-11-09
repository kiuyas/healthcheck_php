/*
============================================================
  健康チェックシートアプリ
  by kiuyas(https://github.com/kiuyas)
============================================================
------------------------------
  入力画面処理
------------------------------
*/
$(document).ready(function(){
    // 初期値を表示し、イベント処理を設定する
    var ym = getYearMonth();
    $('#txtYear').val(ym[0]);
    $('#txtMonth').val(ym[1]);
    $('#btnNext').on('click', btnNext_Click);

    /**
     * 年月の初期値(前月値)を取得する
     */
    function getYearMonth() {
        var d = new Date();
        var year = d.getFullYear();
        var month = d.getMonth() + 1;
        month--;
        if (month == 0) {
            month = 12;
            year--;
        }
        return [year, month];
    }

    /**
     * 次へボタン押下時処理
     * @param {*} ev イベントオブジェクト
     */
    function btnNext_Click(ev) {
        if (validate()) {
            $('#frmMain').submit();
        }
    }

    /**
     * 入力値チェック
     * @returns チェック結果(OK=true, NG=false)
     */
    function validate() {
        var name = $('#txtName').val().trim();
        var year = $('#txtYear').val().trim();
        var month = $('#txtMonth').val().trim();

        // 空チェック
        if (name == '') {
            showInputError('氏名を入力してください。');
            return false;
        }
        if (year == '' || month == '') {
            showInputError('年月を入力してください。');
            return false;
        }

        // 日付チェック
        var date = year + '/' + month + '/1';
        var d = new Date(date);
        if (d.toString() === "Invalid Date") {
            showInputError('年月を正しく入力してください。');
            return false;
        }

        return true;
    }

    /**
     * 入力エラー表示
     * @param {*} message エラーメッセージ
     */
    function showInputError(message) {
        alert(message);
        scrollTo(0, 0);
    }
});
    