<?php
require_once('config.php');
require_once('functions.php');
session_start();

$id = $_POST[id];
$item_text = $_POST[item_text];

$pdo = connectDb();

if ($id) {
    // 編集処理
    $sql = "UPDATE item SET item_text = :item_text, id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":item_text", $item_text);
    $stmt->bindValue(":id", $id);

} else {

    $sql = "INSERT INTO item(item_text, created_at, update_at) values(:item_text, now(), now())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":item_text", $item_text);
}

$flag = $stmt->execute();

unset($pdo);
header("location:index.php");
exit;
?>