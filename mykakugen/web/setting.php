<?php
require_once('config.php');
require_once 'functions.php';
session_start();
// ・ログインチェック
if(!isset($_SESSION[USER])) {
    header("location:login.php");
    exit;
}

// ・セッションからユーザ情報を取得
$user = $_SESSION[USER];

// ・初回アクセス／フォームサブミット振り分け
if ($_SERVER['REQUEST_METHOD'] != "POST") {

    $delivery_hour = $user[delivery_hour];

} else {

    $delivery_hour = $_POST[delivery_hour];

    // ・入力チェック
    $err = array();
    $complete_massege = "";

    if ($delivery_hour == "") {
        $err[delivery_hour] = "送信時間を選んでください。";
    }

    $pdo = connectDb();

    if (empty($err)) {
        // ・userテーブル更新（delivery_hour、updated_at）
        $sql = "UPDATE user set delivery_hour = :delivery_hour, updated_at = now() where id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":delivery_hour" => $delivery_hour, ":id" => $user[id]));

        // ・完了メッセージ表示
        $complete_massege = "時間を設定しました。";

        // ・セッション上のユーザデータを更新
        $user[delivery_hour] = $delivery_hour;
        $_SESSION[USER] = $user;

    }



    unset($pdo);


}




?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>設定 | <?php echo SERVICE_NAME;?></title>
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
            <li><a href="item_list.php">格言リスト</a></li>
            <li class="active"><a href="setting.php">設定</a></li>
            <li><a href="logout.php">ログアウト</a></li>
            </ul>
      	</div>
      </div>
      </div>
    <h1>設定</h1>

    <?php if($complete_massege):?>
      <div class="alert alert-success">
          <?php echo $complete_massege; ?>
      </div>
    <?php endif; ?>


    <form method="POST" class="panel panel-default panel-body">
      <label>メール通知</label>
      <div class="form-group <?php if($err[delivery_hour]) echo "has-error";?>"><span class="help-block"><?php echo $err[delivery_hour];?></span>
         <?php echo arrayToSelect("delivery_hour", $delivery_hours_array, $delivery_hour);?>
      </div>
      <br>

      <input type="submit" class="btn btn-success btn-block" value="登録">
    </form>

    <a href="index.php">戻る</a>

    <hr>
    <footer class="footer">
    	<p><?php echo COPYRIGHT;?></p>
    </footer>


    </div><!-- container -->
  </body>
</html>