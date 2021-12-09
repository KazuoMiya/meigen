<?php
require_once 'config.php';
require_once 'functions.php';
session_start();


if(!isset($_SESSION[USER])) {
    header("location:login.php");
    exit;
}


  $id = $_GET[id];

  $pdo = connectDb();

  $stmt = $pdo->prepare("delete from item where id = :id");
  $stmt->bindValue(":id", $id);
  $stmt->execute();

unset($pdo);

header("location:item_list.php");
exit;
 ?>