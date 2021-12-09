<?php
require_once('config.php');
require_once 'functions.php';


if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST') {

  echo '<html><head><meta charset="utf-8"></head><body>不正なアクセスです。</body></html>';

  exit;

} else {

mb_language("japanese");
mb_internal_encoding("UTF-8");

$pdo = connectDb();

$sql = "select * from user";
$stmt = $pdo->query($sql);

$body = "";

// 1.全ユーザーをデータベースから取得し、ループ。
while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {

  // 2.そのユーザーのdelivery_hourは現在の時刻と一致しているか？
  if ($user['delivery_hour'] == date("G")) {

    // 3.一致していれば、そのユーザーは配信対象なので, itemテーブルから対象ユーザーのアイテムをランダムで1件取得。
    $sql = "SELECT * from item where user_id = :user_id ORDER BY RAND() limit 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":user_id"=>$user[id]));
    $item = $stmt->fetch();

    $body =$item["item_text"].PHP_EOL;
    // var_dump($body);
    // exit;

    if (!empty($item)) {
      // 4.取得出来たらメール送信。
      mb_send_mail($user['user_email'],"【マイカクゲン】今日のカクゲン", $body);
    }

  }
}

unset($pdo);

exit;
}

?>
