<?php
include_once "../base.php";

$News = new DB('news');
$Log = new DB('log');

$id = $_POST['id'];
$user = $_POST['user'];
$type = $_POST['type'];

$post=$News->find($id);


switch ($type) {
    case "1":
        $Log->save(['news' => $id, 'user' => $user]);
        $post['good']++;
        $News->save($post);
        break;
    case "2":
        $Log->del(['news' => $id, 'user' => $user]);
        $post['good']--;
        $News->save($post);
        break;
    default:
        break;
}

?>
