<?php
require_once('config.php');
require_once('functions.php');
session_start();
// $user = $_SESSION[USER];
if(!isset($_SESSION[USER])) {
    header("location:login.php");
    exit;
}

$user = $_SESSION[USER];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

// CSRF対策↓
setToken();

} else {

// CSRF対策↓
checkToken();

    $item_text = $_POST[item_text];
    $pdo = connectDb();

    $err = array();
    $complete_msg = "";

    // 格言が空
    if ($item_text == '') {
        $err[item_text] = '格言を入力して下さい。';
    }

    if (empty($err)) {

        $sql = "INSERT INTO item(user_id, item_text, created_at, updated_at) values(:user_id, :item_text, now(), now())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":user_id"=>$user[id], ":item_text"=>$item_text));

        $complete_msg = "登録されました。<br />続けて登録する場合は下に入力してください。";
        $item_text = "";

    }
    unset($pdo);


}

?>
<!DOCTYPE html>
<html>
   <head>
    <meta charset="utf-8">
    <title>HOME | <?php echo SERVICE_NAME;?></title>
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
      	    <li class="active"><a href="./index.php">格言登録</a></li>
            <li><a href="item_list.php">格言リスト</a></li>
            <li><a href="setting.php">設定</a></li>
            <li><a href="logout.php">ログアウト</a></li>
            </ul>
      	</div>
      </div>
      </div>
    <h1>HOME</h1>

     <?php if ($complete_msg): ?>
        <div class="alert alert-success">
          <?php echo $complete_msg; ?>
        </div>
      <?php endif; ?>


    <form method="POST" class="panel panel-default panel-body">
      <label>あなたの格言を登録して下さい。</label>
      <div class="form-group <?php if ($err['item_text'] != '') echo 'has-error'; ?>">
      <input type="text" class="form-control" name="item_text" value="<?php echo h($item_text); ?>" /><span class="help-block"><?php echo $err['item_text']; ?></span>
      </div>
      <input type="submit" class="btn btn-success btn-block" value="登録">
      <input type="hidden" name="token" value="<?php echo h($_SESSION['sstoken']); ?>" />
    </form>

<hr>
    <footer class="footer">
    	<p><?php echo COPYRIGHT;?></p>
    </footer>

  </div><!-- container -->
  </body>
</html>
