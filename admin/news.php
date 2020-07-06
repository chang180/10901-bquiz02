<fieldset>
    <legend>最新文章管理</legend>
    <form action="api/admin_news.php" method="post">
        <table style="width:80%;margin:auto;">
            <tr class="ct">
                <td>編號</td>
                <td width="70%">標題</td>
                <td>顯示</td>
                <td>刪除</td>
            </tr>
            <?php
            $db = new DB('news');
            $rows = $db->all();

            $total = $db->count();
            $div = 3;
            $pages = ceil($total / $div);
            $now = $_GET['p'] ?? "1";
            $start = ($now - 1) * $div;

            $rows = $db->all("", " LIMIT $start,$div");
            foreach ($rows as $row) {


                $checked = ($row['sh'] == 1) ? "checked" : "";
            ?>
                <tr>
                    <td><?= $row['id']; ?>.</td>
                    <td><?= $row['title']; ?></td>
                    <td>
                        <input type="checkbox" name="sh[]" value="<?= $row['id']; ?>" <?= $checked; ?>>
                    </td>
                    <td>
                        <input type="checkbox" name="del[]" value="<?= $row['id']; ?>">
                    </td>
                    <input type="hidden" name="id[]" value="<?= $row['id']; ?>">
                </tr>
            <?php
            }

            ?>
        </table>
        <div>
            <?php

            if (($now - 1) > 0) {

                echo "<a href='?do=news&p=" . ($now - 1) . "'> < </a>";
            }

            for ($i = 1; $i <= $pages; $i++) {
                $fontSize = ($i == $now) ? "30px" : "20px";

                echo "<a href='?do=news&p=$i' style='font-size:$fontSize'> $i </a>";
            }

            if (($now + 1) <= $pages) {

                echo "<a href='?do=news&p=" . ($now + 1) . "'> > </a>";
            }

            ?>
        </div>

        <div class="ct">
            <input type="submit" value="確定刪除">
        </div>
    </form>

</fieldset>