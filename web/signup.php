<?php
require_once('config.php');
require_once 'functions.php';
session_start();
session_regenerate_id(true);

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  // 初めて画面にアクセスしたときの処理

  // CSRF対策↓
  setToken();

} else {
  // フォームからサブミットされた時の処理

  // CSRF対策↓
  checkToken();

  // 処理1
  // 入力されたニックネーム、メールアドレス、パスワードを受け取り、変数に入れる。
  $user_name = $_POST['user_screen_name'];
  $user_email = $_POST['user_email'];
  $user_pass = $_POST['user_password'];


  // 処理2
  // データベースに接続する（PDOを使う）
  $pdo = connectDb();

  $err = array();

  if ($user_name == "") {
    $err[user_screen_name] = 'ユーザー名を入力してください。';
  }

  $email_check = filter_var($user_email, FILTER_VALIDATE_EMAIL);

  if ($user_email == "") {
    $err[user_email] = 'Emailを入力してください。';
  } else {

    if ($email_check == false) {

      $err[user_email] = 'Emailの形式ではありません。';

    } else {

      //存在チェック
      $user_email_checkEmail = checkEmail($user_email, $pdo);

      if ($user_email_checkEmail) {

        $err[user_email] = '既に存在しています。';

      }

      //             $stmt = $pdo->prepare("SELECT * from user where user_email = :user_email");
      //             $stmt->bindValue(':user_email', $user_email);
      //             $stmt->execute();
      //             $row = $stmt->fetch();

      //             $sql = "SELECT * from user where user_email = :user_email";
      //             $stmt = $pdo->prepare($sql);
      //             $stmt->execute(array(":user_email" => $mail_address));
      //             $user = $stmt->fetch();
    }
  }

  if ($user_pass == "") {
    $err[user_password] = 'パスワードを入力してください。';
  }

  if (empty($err)) {
    // 処理3
    // データベース（userテーブル）に新規登録する。
    $stmt = $pdo->prepare("INSERT INTO user(user_name, user_password, user_email, delivery_hour, created_at, updated_at) VALUES (:user_name, :user_password, :user_email, 99, now(), now())");
    $stmt->bindValue(":user_name", $user_name);
    $stmt->bindValue(":user_password", $user_pass);
    $stmt->bindValue(":user_email", $user_email);
    $stmt->execute();

    // 登録時に管理者にメール送信
    mb_language("japanese");
    mb_internal_encoding("utf-8");

    $body = "新規登録者 : ".$user_name.PHP_EOL."Email : ".$user_email;
    mb_send_mail(ADMIN_EMAIL,"新規ユーザーが登録されました！",$body);

    // 自動ログイン
    $user = getUser($user_email, $user_pass, $pdo);
    $_SESSION['USER'] = $user;

    unset($pdo);

    // 処理4
    // signup_complete.phpに画面遷移する。
    header("location:signup_complete.php");
    exit;

  }

  unset($pdo);

}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>ユーザー登録 | <?php echo SERVICE_NAME;?></title>
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
    <h1>ユーザー登録</h1>

    <form method="POST" class="panel panel-default panel-body">

      <div class="form-group <?php if ($err[user_screen_name] != "") echo  "has-error";?>">
        <input type="text" class="form-control" name="user_screen_name" value="<?php echo $user_name;?>" placeholder="ニックネーム"/><span class="help-blok"><?php echo $err[user_screen_name];?></span>
      </div>


      <div class="form-group <?php if ($err[user_password] != "") echo  "has-error";?>">
        <input type="password" class="form-control" name="user_password" value="<?php echo $user_pass;?>" placeholder="パスワード"/><span class="help-blok"><?php echo $err[user_password];?></span>
      </div>


      <div class="form-group <?php if ($err[user_email] != "") echo  "has-error";?>">
        <input type="text" class="form-control" name="user_email" value="<?php echo $user_email;?>" placeholder="メールアドレス"/><span class="help-blok"><?php echo $err[user_email];?></span>
      </div>

      <input type="submit" value="アカウントを作成">

      <input type="hidden" name="token" value="<?php echo h($_SESSION['sstoken']); ?>" />
    </form>

    <hr>
    <footer class="footer">
      <p><?php echo COPYRIGHT;?></p>
    </footer>


  </div><!-- container -->
</body>
</html>
