<?php
session_start();

// 送信ボタンが押されたかどうか
if (isset($_POST['submit'])) { // #1

  // POSTされたデータをエスケープ処理して変数に格納
  $kinds = htmlspecialchars($_POST['kinds'], ENT_QUOTES | ENT_HTML5);
  $name  = htmlspecialchars($_POST['name'], ENT_QUOTES | ENT_HTML5);
  $email = htmlspecialchars($_POST['email'], ENT_QUOTES | ENT_HTML5);
  $title = htmlspecialchars($_POST['title'], ENT_QUOTES | ENT_HTML5);
  $body = htmlspecialchars($_POST['body'], ENT_QUOTES | ENT_HTML5);

  $errors = []; //エラー格納用配列
  if (trim($name) === '' || trim($name) === "　") {
    $errors['name'] = "名前を入力してください";
  }
  if (trim($title) === '' || trim($title) === "　") {
    $errors['title'] = "タイトルを入力してください";
  }
  if (trim($body) === '' || trim($body) === "　") {
    $errors['body'] = "内容を入力してください";
  }
  // エラー配列がなければ完了
  if (count($errors) === 0) { // #2
    $_SESSION['kinds'] = $kinds; //⇦エスケープ処理をして値を変数に格納済みの入力値
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['title'] = $title;
    $_SESSION['body'] = $body;
    // URLは「http」or「https」に注意
    header('Location:http://localhost/Questionform/confirm.php');
    // header('Location:http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/confirm.php');
  } else {   // #2
    // エラー配列があればエラーを表示
    echo $errors['name'];
    echo $errors['title'];
    echo $errors['body'];
  }  // #2
}  // #1

// confirm.phpから戻ってきたときに値を保持
if (isset($_GET) && isset($_GET['action']) && $_GET['action'] === 'edit') {
  $kinds = $_SESSION['kinds'];
  $name  = $_SESSION['name'];
  $email = $_SESSION['email'];
  $title = $_SESSION['title'];
  $body  = $_SESSION['body'];
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <form method="post" action="form.php">
    <div>
      <p>お問い合わせ種別</p>
      <input type="radio" name="kinds" value="質問" <?php if (isset($kinds) && $kinds === "質問") {echo "checked";} else {echo "checked";} ?>><label>質問</label>
      <input type="radio" name="kinds" value="依頼" <?php if (isset($kinds) && $kinds === "依頼") {echo "checked";} ?>><label>依頼</label>
      <input type="radio" name="kinds" value="その他" <?php if (isset($kinds) && $kinds === "その他") {echo "checked";} ?>><label>その他</label>
    </div>
    <p>お名前</p>
    <input type="text" name="name" value="<?php if (isset($name)) {echo $name;} ?>" placeholder="お名前" required>
    <p>メールアドレス</p>
    <input type="email" name="email" value="<?php if (isset($email)) {echo $email;} ?>" placeholder="メールアドレス" required>
    <p>件名</p>
    <input type="text" name="title" value="<?php if (isset($title)) {echo $title;} ?>" placeholder="件名" required>
    <p>内容</p>
    <textarea type="text" name="body" placeholder="お問い合わせ内容" rows="7" required><?php if (isset($body)) {echo $body;} ?></textarea><button type="submit" name="submit" value="確認">確認</button>
  </form>
</body>
</html>
