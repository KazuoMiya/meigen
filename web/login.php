<?php
require_once('config.php');
require_once('functions.php');
session_start();
session_regenerate_id(true);

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // 初めて画面にアクセスしたときの処理

    // CSRF対策↓
	setToken();

} else {
//     1.画面から入力データを受取り
    $user_email = $_POST[user_email];
    $user_pass = $_POST[user_password];


	// CSRF対策↓
	checkToken();

    // データベースに接続する（PDOを使う）
    $pdo = connectDb();

//     2.入力データをチェックし

    $err = array();

    if ($user_email == "") {
        $err[user_email] = "メールアドレスを入力してください。";
    } else {
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $err[user_email] = "メールの形式が違っています";
        } else {

            // Email存在チェック
            if (!checkEmail($user_email, $pdo)) {
                $err[user_email] = "メールアドレスが存在しません";
            }

        }
    }

    if ($user_pass =="") {
        $err[user_password] = "パスワードを入力してください。";
    } else {

        //　パスワードチェック（user検索）
        $user = getUser($user_email, $user_pass, $pdo);

        if ($user == false) {
            $err[user_password] = "パスワードが正しくない";
        }
    }

    if (empty($err)) {

        $_SESSION[USER] = $user;

        header("location:index.php");
        unset($pdo);
        exit;
    }

    unset($pdo);
}



?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title><?php echo SERVICE_NAME;?></title>
    <meta name="description" content="自分だけの格言をいつも忘れないために。格言リマインダー「マイカクゲン」" />
    <meta name="keywords" content="マイカクゲン,格言,リマインダー" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <link href="css/mykakugen.css" rel="stylesheet">
  </head>

  <body id="main">
  <div class="nav navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
  	<div class="container">
  	  <a class="navbar-brand" href="<?php echo SITE_URL;?>"><?php echo SERVICE_SHORT_NAME;?></a>
  	    <ul class="nav navbar-nav">
      	    <li><a href="./index.php">格言登録</a></li>
            <li><a href="item_list.php">格言リスト</a></li>
            <li><a href="setting.php">設定</a></li>
            <li class="active"><a href="logout.php">ログアウト</a></li>
        </ul>
  	</div>
  </div>
  </div>

    <div class="container">

      <div class="row">

        <div class="col-md-9">
          <div class="jumbotron">

          <h1>大切なことを忘れないために。</h1>
          <p>あなたが忘れたくない言葉や格言をマイカクゲンに登録して下さい。<br />毎日一つ、ランダムにメールでお知らせするシンプルなサービスです。</p>
          <p><a href="./signup.php" class="btn btn-success btn-lg">新規ユーザー登録（無料） &raquo;</a></p>
          </div>

        <div class="row">

            <div class="col-md-4">
               <div class="panel panel-default">
                 <div class="panel-heading">
                  <h2 class="panel-title">どんなことに使えるの？</h2>
                 </div>
                   <div class="panel-body">
                    <p>今日感じた熱い思い、忘れたくない自分の夢、成功体験や失敗体験、教訓、自分のモチベーションが上がる一言、誰かの格言、自分だけの格言、家族や夫婦の決め事、忘れたくない相手への気持ちなど、思いついたときにいつでも登録しておきましょう。もしもあなたが忘れてしまっても、いつかマイカクゲンが思い出させてくれます。</p>
                   </div>
             </div>
          </div>

          <div class="col-md-4">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h2 class="panel-title">お金がかかる？</h2>
              </div>
                  <div class="panel-body">
                    <p>全て無料です。</p>
                 </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="panel panel-default">
                 <div class="panel-heading">
                  <h2 class="panel-title">登録内容は他人にも見られるの？</h2>
                     </div>
                        <div class="panel-body">
                          <p>登録した内容は自分のみ見ることができます。</p>
                        </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="sidebar-nav panel panel-default">
        	<div class="panel-heading">
        		<h2 class="panel-title">ログイン</h2>
        	</div>
        	<div class="panel-body">
             <form method="POST">
               <div class="form-group <?php if($err[user_email]) echo "has-error";?>">
                      <label>メールアドレス
                      <input type="text" class="form-control" name="user_email" value="<?php echo $user_email?>" /></label><span class="help-block"><?php echo $err[user_email];?></span>
               </div>

              <div class="form-group <?php if($err[user_password]) echo "has-error";?>">
                  <label>パスワード
                  <input type="password" class="form-control" name="user_password" value=""/></label><span class="help-block"><?php echo $err[user_password];?></span>
              </div>

              <div class="form-group">
              	<input type="submit" value="ログイン" class="btn btn-primary btn-block">
              </div>

              <div class="form-group">
              	<input type="checkbox">次回から自動でログイン
              </div>
              <input type="hidden" name="token" value="<?php echo h($_SESSION['sstoken']); ?>" />
            </form>
            </div><!--/panel-body-->
          </div><!--/sidebar-nav panel panel-default-->
      </div><!--/col-md-3-->
    </div><!--/row-->


        <hr>
    <footer class="footer">
    	<p><?php echo COPYRIGHT;?></p>
    </footer>

  </div>

  </body>
</html>
