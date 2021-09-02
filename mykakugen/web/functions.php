<?php
require_once('config.php');

// DB接続
function connectDb() {

    $param = "mysql:dbname=".DB.";host=".HOST;
    $pdo = new PDO($param, USER, PASS);
    $pdo->query('SET NAMES utf8;');

    return $pdo;
}

// Email存在チェック
function checkEmail($user_email, $pdo) {
    $sql = "SELECT user_email from user where user_email = :user_email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":user_email" => $user_email));
    $user = $stmt->fetch();

    return $user?true:false;
}

// Email, pass からuser検索
function getUser($user_email, $user_pass, $pdo) {

    $sql = "SELECT * from user where user_email = :user_email and user_password = :user_password";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":user_email" =>$user_email, ":user_password" => $user_pass));
    $user = $stmt->fetch();

    return $user ? $user : false;

}

// プルダウン生成
function arrayToSelect($inputName, $srcArray, $selectedIndex = "") {

//     $tempHtml = "<select class = 'form-control' name = '".$inputName."' >"."\n";
    $temphtml = '<select class="form-control" name="'. $inputName. '">'. "\n";

    foreach ($srcArray as $key => $val){


        if ($selectedIndex == $key) {
                $selectedText = ' selected="selected"';
              } else {
                $selectedText = '';
              }

//               $tempHtml .= "<option value='".$key."'".$selectedText."> ".$val."</option>"."\n";
              $temphtml .= '<option value="'. $key. '"'. $selectedText. '>'. $val. '</option>'. "\n";
    }

    $tempHtml .= '</select>'. "\n";

    return $temphtml;
}

function h($original_str) {
	return htmlspecialchars($original_str, ENT_QUOTES, "UTF-8");
}

// トークンを発行する処理

function setToken() {

    $token = sha1(uniqid(mt_rand(), true));

    $_SESSION['sstoken'] = $token;

}



// トークンをチェックする処理

function checkToken() {

    if (empty($_SESSION['sstoken']) || ($_SESSION['sstoken'] != $_POST['token'])) {

        echo '<html><head><meta charset="utf-8"></head><body>不正なアクセスです。</body></html>';

        exit;

    }

}

?>
