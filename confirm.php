<?php
// ============================================================
//   健康チェックシートアプリ
//   by kiuyas(https://github.com/kiuyas)
// ============================================================
date_default_timezone_set('Asia/Tokyo');
require_once('./data.php');

// ------------------------------
//  処理
// ------------------------------
$inputData = $_POST;

if ($inputData["year"]) {
    setup();
} else {
    header('Location: index.php');
    exit;
}

function setup() {
    global $data, $inputData;

    $inputData["jpYear"] = '令和' . ($inputData["year"] - 2018);
    $inputData["pagetitle"] = $data["titlePrefix"] . $data["title"] . "(" . $inputData["jpYear"] . "年" . $inputData["month"] . "月". ")" . $inputData["name"];
    $inputData["date"] = $inputData["jpYear"] . '年' . $inputData["month"] . '月';
    $inputData["score1"] = calc1($inputData);
    $inputData["judge1"] = judge1($inputData["score1"]);
    $inputData["score2"] = calc2($inputData);
    $inputData["judge2"] = judge2($inputData["score2"]);
    $inputData["score3"] = calc3($inputData["judge1"], $inputData["judge2"]);
    $inputData["printDate"] = getDateTimeString();
}

/**
 * 評価1の計算
 * @param {*} d パラメータ
 * @returns 計算結果
 */
function calc1($d) {
    global $data;

    $score = 0;
    for($i = 0; $i < count($data["textsA1"]); $i++) {
        $score += $data["scoreA"][$d['opt1_' . $i]];
    }
    return $score;
}

/**
 * 評価1の判定
 * @param {*} score 点数
 * @returns 判定結果
 */
function judge1($score) {
    if ($score >= 0 && $score <= 4) {
        return 0;
    } else  if ($score >= 5 && $score <= 10) {
        return 1;
    } else  if ($score >= 11 && $score <= 20) {
        return 2;
    }
    return 3;
}

/**
 * 評価2の計算
 * @param {*} d パラメータ
 * @returns 計算結果
 */
function calc2($d) {
    global $data;

    $score = 0;
    for ($i = 0; $i < count($data["textsB1"]); $i++) {
        $score += $data["scoreB"][$d['opt2_' . $i]];
    }
    return $score;
}

/**
 * 評価2の判定
 * @param {number} score 点数
 * @returns 判定結果
 */
function judge2($score) {
    if ($score == 0) {
        return 0;
    } else  if ($score >= 1 && $score <= 2) {
        return 1;
    } else  if ($score >= 3 && $score <= 5) {
        return 2;
    }
    return 3;
}

/**
 * 総合評価の判定
 * @param {*} judge1 評価1の判定
 * @param {*} judge2 評価2の判定
 * @returns 判定結果
 */
function calc3($judge1, $judge2) {
    $arr = [
        [0, 0, 2, 4],
        [0, 1, 3, 5],
        [0, 2, 4, 6],
        [1, 3, 5, 7],
    ];

    return $arr[$judge1][$judge2];
}

/**
 * 日時文字列の取得
 * @returns 日時文字列(yyyy/MM/dd HH:mm:ss)
 */
function getDateTimeString() {
    return date("Y/m/d H:i:s");
}

// ------------------------------
//   確認・印刷画面テンプレート
// ------------------------------
?>
<!DOCTYPE html>
<html>

<head>
    <title><?=$inputData["pagetitle"]?></title>
    <link rel="icon" href="img/favicon.ico" />
    <link rel='stylesheet' href='css/common.css' />
    <link rel='stylesheet' href='css/confirm.css' />
</head>

<body>
    <div class="controlPanel">
        <button id="btnPrint" onclick="window.print();">印刷</button>
        <button id="btnBack" onclick="history.back();">戻る</button>
    </div>
    <h1><?=$data["title"]?></h1>
    <h3>氏名</h3>
    <div class="general">
        <?=$inputData["name"]?>
    </div>
    <h3>年月</h3>
    <div class="general">
        <?=$inputData["date"]?>
    </div>
    <div>
            <h3>評価(1)</h3>
        <div class="desc1">最近１か月間の自覚症状について、各質問に対して最も当てはまる項目を選択してください。</div>
        <table class="items1">
            <?php
            for($i = 0; $i < count($data["textsA1"]); $i++) {
            ?>
                <tr>
                <th id="q1_<?=$i?>" class="q q1"><?=$data["textsA1"][$i]?></th>
                <?php
                for($j = 0; $j < count($data["textsA2"]); $j++) {
                    if ($data["textsA2"][$j]) {
                        $tempClass = "";
                        if ($inputData['opt1_' . $i] == $j) {
                            $tempClass = "hit";
                        }
                        ?>
                        <td id="a1_<?=$i?>" class="q1 <?=$tempClass?>">
                            <?=$data["textsA2"][$j]?> (<?=$data["scoreA"][$j]?>)
                        </td>
                    <?php
                    } else {
                    ?>
                        <td id="a1_<?=$i?>" class="q1 empty">
                        </td>
                    <?php
                    }
                }
                ?>
                </tr>
            <?php
            }
            ?>
        </table>
        <?php
        $hitStyle = 'class="hit"';
        ?>
        <div class="eva1">
            <table class="eva1">
                <tr>
                    <th>Ⅰ</th><td id="eva1_1" <?= ($inputData["judge1"] == 0 ? $hitStyle : "")?> >０～４点</td>
                    <th>Ⅱ</th><td id="eva1_2" <?= ($inputData["judge1"] == 1 ? $hitStyle : "")?> >５～１０点</td>
                    <th>Ⅲ</th><td id="eva1_3" <?= ($inputData["judge1"] == 2 ? $hitStyle : "")?> >１１～２０点</td>
                    <th>Ⅳ</th><td id="eva1_4" <?= ($inputData["judge1"] == 3 ? $hitStyle : "")?> >２１点以上</td>
                </tr>
            </table>
        </div>
    </div>
    <div>
        <h3>評価(2)</h3>
        <div class="desc2">最近１か月間の勤務の状況について、各質問に対し最も当てはまる項目を選択してください。</div>
        <table class="items2">
        <?php
        for($i = 0; $i < count($data["textsB1"]); $i++) {
        ?>
        <tr>
            <th id="q2_<?=$i?>" class="q q2"><?=$data["textsB1"][$i]?></th>
            <?php
            for($j = 0; $j < count($data["textsB2"][$i]); $j++) {
                if ($data["textsB2"][$i][$j]) {
                    $tempClass = "";
                    if ($inputData['opt2_' . $i] == $j) {
                        $tempClass = "hit";
                    }
                    ?>
                    <td id="a2_<?=$i?>" class="q2 <?=$tempClass?>">
                    <?=$data["textsB2"][$i][$j]?>(<?=$data["scoreB"][$j]?>)
                    </td>
                <?php
                } else {
                ?>
                    <td id="a2_<?=$i?>" class="q2 empty">
                    </td>
                <?php
                }
            } ?>
        </tr>
        <?php
        }
        ?>
        </table>
        <div class="eva2">
            <table class="eva2">
                <tr>
                    <th>A</th><td id="eva2_1" <?= ($inputData["judge2"] == 0 ? $hitStyle : "") ?>>０点</td>
                    <th>B</th><td id="eva2_1" <?= ($inputData["judge2"] == 1 ? $hitStyle : "") ?>>１～２点</td>
                    <th>C</th><td id="eva2_1" <?= ($inputData["judge2"] == 2 ? $hitStyle : "") ?>>３～５点</td>
                    <th>D</th><td id="eva2_1" <?= ($inputData["judge2"] == 3 ? $hitStyle : "") ?>>６点以上</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="eva3">
        <h3>総合判定</h3>
        <table class="eva3">
            <tr>
                <th colspan="2" rowspan="2"></th>
                <th colspan="4">勤務の状況</th>
            </tr>
            <tr>
                <th style="width: 48px;" <?=$inputData["judge2"] == 0 ? 'class="hit"' : ""?>>A</th>
                <th style="width: 48px;" <?=$inputData["judge2"] == 1 ? 'class="hit"' : ""?>>B</th>
                <th style="width: 48px;" <?=$inputData["judge2"] == 2 ? 'class="hit"' : ""?>>C</th>
                <th style="width: 48px;" <?=$inputData["judge2"] == 3 ? 'class="hit"' : ""?>>D</th>
            </tr>
            <tr>
                <th rowspan="4">自<br/>覚<br/>症<br/>状</th>
                <th <?=$inputData["judge1"] == 0 ? 'class="hit"' : ""?>>Ⅰ</th>
                <td style="text-align: right;" <?=$inputData["judge1"] == 0 && $inputData["judge2"] == 0 ? 'class="hit"' : ""?>>0</td>
                <td style="text-align: right;" <?=$inputData["judge1"] == 0 && $inputData["judge2"] == 1 ? 'class="hit"' : ""?>>0</td>
                <td style="text-align: right;" <?=$inputData["judge1"] == 0 && $inputData["judge2"] == 2 ? 'class="hit"' : ""?>>2</td>
                <td style="text-align: right;" <?=$inputData["judge1"] == 0 && $inputData["judge2"] == 3 ? 'class="hit"' : ""?>>4</td>
            </tr>
            <tr>
                <th <?=$inputData["judge1"] == 1 ? 'class="hit"' : ""?>>Ⅱ</th>
                <td style="text-align: right;" <?=$inputData["judge1"] == 1 && $inputData["judge2"] == 0 ? 'class="hit"' : ""?>>0</td>
                <td style="text-align: right;" <?=$inputData["judge1"] == 1 && $inputData["judge2"] == 1 ? 'class="hit"' : ""?>>1</td>
                <td style="text-align: right;" <?=$inputData["judge1"] == 1 && $inputData["judge2"] == 2 ? 'class="hit"' : ""?>>3</td>
                <td style="text-align: right;" <?=$inputData["judge1"] == 1 && $inputData["judge2"] == 3 ? 'class="hit"' : ""?>>5</td>
            </tr>
            <tr>
                <th <?=$inputData["judge1"] == 2 ? 'class="hit"' : ""?>>Ⅲ</th>
                <td style="text-align: right;" <?=$inputData["judge1"] == 2 && $inputData["judge2"] == 0 ? 'class="hit"' : ""?>>0</td>
                <td style="text-align: right;" <?=$inputData["judge1"] == 2 && $inputData["judge2"] == 1 ? 'class="hit"' : ""?>>2</td>
                <td style="text-align: right;" <?=$inputData["judge1"] == 2 && $inputData["judge2"] == 2 ? 'class="hit"' : ""?>>4</td>
                <td style="text-align: right;" <?=$inputData["judge1"] == 2 && $inputData["judge2"] == 3 ? 'class="hit"' : ""?>>6</td>
            </tr>
            <tr>
                <th <?=$inputData["judge1"] == 3 ? 'class="hit"' : ""?>>Ⅳ</th>
                <td style="text-align: right;" <?=$inputData["judge1"] == 3 && $inputData["judge2"] == 0 ? 'class="hit"' : ""?>>1</td>
                <td style="text-align: right;" <?=$inputData["judge1"] == 3 && $inputData["judge2"] == 1 ? 'class="hit"' : ""?>>3</td>
                <td style="text-align: right;" <?=$inputData["judge1"] == 3 && $inputData["judge2"] == 2 ? 'class="hit"' : ""?>>5</td>
                <td style="text-align: right;" <?=$inputData["judge1"] == 3 && $inputData["judge2"] == 3 ? 'class="hit"' : ""?>>7</td>
            </tr>
        </table>
    </div>
    <h3>出力日時</h3>
    <div class="general">
        <?=$inputData["printDate"]?>
    </div>
</body>

</html>