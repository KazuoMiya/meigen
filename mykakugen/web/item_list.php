<?php
require_once('config.php');
require_once('functions.php');
session_start();
// $user = $_SESSION[USER];
if(!isset($_SESSION[USER])) {
    header("location:login.php");
    exit;
}
// セッションからユーザ情報を取得
$user = $_SESSION[USER];

$pdo = connectDb();

$items = array();

// $sql = "select * from item";
$sql = "select * from item where user_id = :user_id order by created_at desc";
$stmt = $pdo->prepare($sql);
$param = array(":user_id"=>$user[id]);
$stmt->execute($param);
foreach ($stmt->fetchAll() as $row){
    array_push($items, $row);
}
unset($pdo);
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>あなたが登録した格言 | <?php echo SERVICE_NAME;?></title>
    <meta name="description" content="自分だけの格言をいつも忘れないために。格言リマインダー「マイカクゲン」" />
    <meta name="keywords" content="マイカクゲン,格言,リマインダー" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <link href="css/mykakugen.css" rel="stylesheet">
  </head>

  <body id="main">
  <div class="container">
      <div class="nav navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
      	<div class="container">
      	  <a class="navbar-brand" href="<?php echo SITE_URL;?>"><?php echo SERVICE_SHORT_NAME;?></a>
      	    <ul class="nav navbar-nav">
      	    <li><a href="./index.php">格言登録</a></li>
            <li class="active"><a href="item_list.php">格言リスト</a></li>
            <li><a href="setting.php">設定</a></li>
            <li><a href="logout.php">ログアウト</a></li>
            </ul>
      	</div>
      </div>
      </div>
    <h1>あなたが登録した格言</h1>
    <ul class='list-group'>
   <?php foreach ($items as $item): ?>

       <li class="list-group-item">
       <?php echo $item[item_text]; ?>&nbsp;&nbsp;
        <a href="item_edit.php?id=<?php echo $item[id]; ?>">[編集]</a>&nbsp;&nbsp;
        <a href="javascript:void(0);" onclick="var ok=confirm('削除しても宜しいですか?');
        if (ok) location.href='delete.php?id=<?php echo $item['id']; ?>'; return false;">[削除]</a>
       </li>
      <?php endforeach; ?>
    </ul>


    <a href="index.php">戻る</a>

    <hr>
    <footer class="footer">
    	<p><?php echo COPYRIGHT;?></p>
    </footer>


    </div><!-- container -->
  </body>
</html>