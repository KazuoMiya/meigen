<?php
require_once('config.php');
require_once 'functions.php';

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>ユーザー登録完了 | <?php echo SERVICE_NAME;?></title>
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
    <h1>ユーザー登録完了</h1>

    <div class="alert alert-success">
    ユーザー登録が完了しました。
    </div>

    <a href="./index.php">トップページへ</a>

    <hr>
    <footer class="footer">
    	<p><?php echo COPYRIGHT;?></p>
    </footer>


  </div><!-- container -->
  </body>
</html>
