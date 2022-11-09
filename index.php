<?php
// ============================================================
//   健康チェックシートアプリ
//   by kiuyas(https://github.com/kiuyas)
// ============================================================
require_once('./data.php');

// ------------------------------
//   入力画面テンプレート
// ------------------------------
?>
<!DOCTYPE html>
<html>

<head>
    <title><?=$data["title"]?></title>
    <link rel="icon" href="img/favicon.ico" />
    <link rel='stylesheet' href='css/common.css' />
    <link rel='stylesheet' href='css/index.css' />
    <script type="text/javascript" src="lib/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
</head>

<body>
    <h1><?=$data["title"]?></h1>
    <div style="margin-bottom: 8px;">
        参考:
        <p class="ref"><a href="https://www.mhlw.go.jp/seisakunitsuite/bunya/koyou_roudou/roudoukijun/karoushizero/pdf/2020_karoushi_zero.pdf">厚生労働省「過労死ゼロ」パンフレット</a></p>
    </div>
    <form id="frmMain" method="post" action="confirm.php">
        <div class="nameForm">
            <div>
                氏名 : <input type="text" name="name" id="txtName"/>
            </div>
            <div>
                年月 : <input type="text" name="year" id="txtYear" style="width: 4em;"/> 年 <input type="text" name="month" id="txtMonth" style="width: 2em;"/> 月
            </div>
        </div>
        <div style="background-color: lightblue;">
        <div class="desc1">(1) 最近１か月間の自覚症状について、各質問に対して最も当てはまる項目を選択してください。</div>
        <dl class="items1">
            <?php
            for($i = 0; $i < count($data["textsA1"]); $i++) {
            ?>
                <dt id="q1_<?=$i?>"><?=$data["textsA1"][$i]?></dt>
                <dd id="a1_<?=$i?>">
                <?php
                for($j = 0; $j < count($data["textsA2"]); $j++) {
                    $rdoName = "opt1_" . $i;
                    $rdoId = "opt1_". $i . "_" . $j;
                    $checked = $j == 0 ? " checked" : "";
                ?>
                    <div class="opt"><input type="radio" name="<?=$rdoName?>" id="<?=$rdoId?>" value="<?=$j?>"<?=$checked?>/><labeL for="<?=$rdoId?>"><?=$data["textsA2"][$j]?></labeL>(<?=$data["scoreA"][$j]?>)</div>
                <?php
                }
                ?>
                </dd>
            <?php
            }
            ?>
        </dl>
    </div>
    <div style="background-color: pink;">
        <div class="desc2">(2) 最近１か月間の勤務の状況について、各質問に対し最も当てはまる項目を選択してください。</div>
        <dl class="items2">
        <?php
        for($i = 0; $i < count($data["textsB1"]); $i++) {
        ?>
            <dt id="q2_<?=$i?>"><?=$data["textsB1"][$i]?></dt>
            <dd id="a2_<?=$i?>">
            <?php
            for($j = 0; $j < count($data["textsB2"][$i]); $j++) {
                if ($data["textsB2"][$i][$j] !== "") {
                    $rdoName = "opt2_" . $i;
                    $rdoId = "opt2_". $i . "_" . $j;
                    $checked = $j == 0 ? " checked" : "";
                    ?>
                        <div class="opt"><input type="radio" name="<?=$rdoName?>" id="<?=$rdoId?>" value="<?=$j?>"<?=$checked?>/><labeL for="<?=$rdoId?>"><?=$data["textsB2"][$i][$j]?>(<?=$data["scoreB"][$j]?>)</labeL></div>
                    <?php
                }
            }
            ?>
            </dd>
        <?php
        }
        ?>
        </dl>

        <div class="notice">
            <p>★１．深夜勤務の頻度や時間数などから総合的に判断して下さい。深夜勤務は、深夜時間帯（午後１０時～午前５時）の一部または全部を含む勤務を言います。</p>
            <p>★２．肉体的作業や寒冷・暑熱作業などの身体的な面での負担</p>
        </div>
    </div>
    </form>

    <button id="btnNext">次へ</button>
</body>

</html>