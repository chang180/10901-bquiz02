<?php
session_start();
date_default_timezone_set("Asia/Taipei");

class DB
{
    private $dsn = "mysql:host=localhost;charset=utf8;dbname=db2";
    private $root = "root";
    private $password = "";
    public function __construct($table)
    {
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, $this->root, $this->password);
    }

    public function all(...$arg)
    {
        $sql = "SELECT * FROM $this->table ";
        if (!empty($arg[0]) && is_array($arg[0])) {
            foreach ($arg[0] as $k => $v) $tmp[] = "`$k`='$v'";
            $sql .= " WHERE " . implode(" && ", $tmp);
        }
        $sql .= $arg[1] ?? '';
        return @$this->pdo->query($sql)->fetchAll();
    }

    public function del($arg)
    {
        $sql = "DELETE FROM $this->table ";
        if (is_array($arg)) {
            foreach ($arg as $k => $v) $tmp[] = "`$k`='$v'";
            $sql .= " WHERE " . implode(" && ", $tmp);
        } else $sql .= " WHERE `id`='$arg'";
        return $this->pdo->exec($sql);
    }

    public function find($arg)
    {
        $sql = "SELECT * FROM $this->table ";
        if (is_array($arg)) {
            foreach ($arg as $k => $v) $tmp[] = "`$k`='$v'";
            $sql .= " WHERE " . implode(" && ", $tmp);
        } else $sql .= " WHERE `id`='$arg'";
        // echo $sql;
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function count(...$arg)
    {
        $sql = "SELECT COUNT(*) FROM $this->table ";
        if (!empty($arg[0]) && is_array($arg[0])) {
            foreach ($arg[0] as $k => $v) $tmp[] = "`$k`='$v'";
            $sql .= " WHERE " . implode(" && ", $tmp);
        }
        $sql .= $arg[1] ?? '';
        // echo $sql;
        return $this->pdo->query($sql)->fetchColumn();
    }

    public function q($sql)
    {
        // echo $sql;
        return $this->pdo->query($sql)->fetchAll();
    }

    public function save($arg)
    {
        if (isset($arg['id'])) {
            foreach ($arg as $k => $v) $tmp[] = "`$k`='$v'";
            $sql = sprintf("UPDATE %s SET %s WHERE `id`='%s'", $this->table, implode(",", $tmp), $arg['id']);
        } else $sql = sprintf("INSERT INTO %s (`%s`) VALUES ('%s')", $this->table, implode("`,`", array_keys($arg)), implode("','", $arg));
        return $this->pdo->exec($sql);
    }
}
function to($url)
{
    header("location:$url");
}


$Total = new DB('total');
$todayVisited = $Total->find(['date' => date("Y-m-d")]);
$sumVisited = $Total->q("SELECT SUM(total) FROM total")[0]['SUM(total)']; //q()會回傳二維陣列，因為是fetchAll();
// var_dump($Total->q("SELECT SUM(total) FROM total"));
// print_r($sumVisited);
// $sumVisited=$Total->q("select sum(`total`) from `total`")[0][0];

// 判斷瀏覽人次
$chk = $Total->find(['date' => date("Y-m-d")]);
if (empty($chk) && empty($_SESSION['visited'])) {
    //沒有今天的資料，也沒有session ， 今天頭香，需要新增今日資料，也要加1
    $Total->save(['date' => date("Y-m-d"), 'total' => 1]);
    $_SESSION['visited'] = 1;
} else if (empty($chk) && !empty($_SESSION['visited'])) {
    //沒有今天的資料，但是有session ，異常情形，需要新增今日資料
    $Total->save(['date' => date("Y-m-d")]);
} else if (!empty($chk) && empty($_SESSION['visited'])) {
    //有今天的資料，但沒有session , 表示是新來 需要加1
    $chk['total']++;
    $Total->save($chk);
    $_SESSION['visited'] = 1;
}
// else{
// //有今天的資料，也有session

// }


// if (empty($_SESSION['visited'])) {
//     if (empty($chk)) {
//         //沒有今天的資料
//         $total->save(['date' => date("Y-m-d"), 'total' => 1]);
//     } else {
//         //有今天的資料
//         $chk['total']++;
//         $Total->save($chk);
//     }
//     $_SESSION['visited'] = 1;
// }
