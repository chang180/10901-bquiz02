<style>
    .all {
        display: none;

        background: rgba(51, 51, 51, 0.8);
        color: #FFF;
        min-height: 100px;
        width: 500px;
        height: 400px;

        position: fixed;
        display: none;
        z-index: 9999;
        overflow: auto;

    }

    .title {
        background: lightgrey;
        cursor: pointer;
    }
</style>
<fieldset>
    <legend>目前位置：首頁 > 人氣文章區</legend>
    <table>
        <tr>
            <td width="20%">標題</td>
            <td width="60%">內容</td>
            <td width="20%">人氣文章</td>
        </tr>
        <?php
        $db = new DB("news");
        $Log = new DB('log');
        $total = $db->count();
        $div = 5;
        $pages = ceil($total / $div);
        $now = $_GET['p'] ?? 1;
        // echo $now;
        $start = ($now - 1) * $div;

        $rows = $db->all(['sh' => 1], " ORDER BY good DESC LIMIT $start,$div");
        foreach ($rows as $row) {
        ?>
            <tr>
                <td class="title"><?= $row['title']; ?></td>
                <td class="tt">
                    <div>
                        <?= mb_substr($row['text'], 0, 30, 'utf8'); ?>...
                    </div>
                    <div class="all"><?= nl2br($row['text']); ?></div>
                </td>
                <td>
                    <span id="vie<?= $row['id']; ?>"><?= $row['good']; ?></span>個人說 <img src="icon/02B03.jpg" style="width:20px">
                    <?php

                    if (!empty($_SESSION['login'])) {
                        $chk = $Log->count(['user' => $_SESSION['login'], 'news' => $row['id']]);
                        if ($chk > 0) {
                            echo "<a href='#' id='good" . $row['id'] . "' onclick='good(" . $row['id'] . ",2,&#39;" . $_SESSION['login'] . "&#39;)'>收回讚</a>";
                        } else {
                            echo "<a href='#' id='good" . $row['id'] . "' onclick='good(" . $row['id'] . ",1,&#39;" . $_SESSION['login'] . "&#39;)'>讚</a>";
                        }
                    }
                    ?>
                </td>
            </tr>
        <?php } ?>
    </table>

    <div>
        <?php

        if (($now - 1) > 0) {

            echo "<a href='?do=pop&p=" . ($now - 1) . "'> < </a>";
        }

        for ($i = 1; $i <= $pages; $i++) {
            $fontSize = ($i == $now) ? "30px" : "20px";

            echo "<a href='?do=pop&p=$i' style='font-size:$fontSize'> $i </a>";
        }

        if (($now + 1) <= $pages) {

            echo "<a href'=?do=pop&p=" . ($now + 1) . "'> > </a>";
        }

        ?>


    </div>


</fieldset>

<script>
    $(".title").hover(function() {
        // $(".all").hide();
        $(this).next().children('.all').toggle();
    })
    $(".tt").hover(function() {
        $(this).children('.all').toggle();
    })
</script>