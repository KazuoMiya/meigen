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
// $user = $_SESSION[USER];

// パラメータで渡されたアイテムIDを取得
$id = $_GET[id];

$pdo = connectDb();

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $sql = "SELECT * from item where id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":id", $id);
    $stmt->execute();

    $item = $stmt->fetch();
    // $id = $row["id"];
    $item_text = $item[item_text];
} else {
    // postから受け取る
//     $id = $_POST[id];
    $item_text = $_POST[item_text];

    $err = array();
    $complete_msg = "";

    if (!$item_text) {
        $err[item_text] = "格言を入力してください";
    }

    if (empty($err)) {
        // 編集処理
        $sql = "UPDATE item SET item_text = :item_text, updated_at = now() where id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":item_text", $item_text);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $complete_msg = "変更しました。";
    }
}

unset($pdo);

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>格言の編集 | <?php echo SERVICE_NAME;?></title>
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
      	</div>
      </div>
      </div>
    <h1>格言の編集</h1>

      <?php if ($complete_msg): ?>
        <div class="alert alert-success">
          <?php echo $complete_msg; ?>
        </div>
      <?php endif; ?>

   <form method="POST" class="panel panel-default panel-body">
   <input type="hidden" name="id" value="<?php echo $id; ?>">
   	<div class="form-group <?php if($err[item_text]) echo "has-error";?>">
      <input type="text" class="form-control" name="item_text" value="<?php echo $item_text; ?>" /><span class="help-block"><?php echo $err[item_text]; ?></span>
      </div>
      <input type="submit" class="btn btn-success btn-block" value="変更">
   </form>

   <a href="item_list.php">戻る</a>

   <hr>
    <footer class="footer">
    	<p><?php echo COPYRIGHT;?></p>
    </footer>

   </div><!-- container -->
  </body>
</html>