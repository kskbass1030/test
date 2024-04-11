<?php
session_start();   //SESSIONを使うときは最初にスタートさせる

if (isset($_SESSION['kinds'])) {
  $kinds = $_SESSION['kinds'];
  $name = $_SESSION['name'];
  $email = $_SESSION['email'];
  $title = $_SESSION['title'];
  $body = $_SESSION['body'];
}
// ここにトークンを生成するコードを記述
$token = sha1(uniqid(mt_rand(), true));
$_SESSION['token'] = $token;

// $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(48));
// $token = htmlspecialchars($_SESSION['token'], ENT_QUOTES);
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
  <div>
    <h2>お問い合わせ内容確認</h2>
    <table>
      <tr>
        <th>お問い合わせ種別</th>
        <td><?php echo $kinds; ?></td>
      </tr>
      <tr>
        <th>お名前</th>
        <td><?php echo $name; ?></td>
      </tr>
      <tr>
        <th>メールアドレス</th>
        <td><?php echo $email; ?></td>
      </tr>
      <tr>
        <th>件名</th>
        <td><?php echo $title; ?></td>
      </tr>
      <tr>
        <th>内容</th>
        <td><?php echo nl2br($body); ?></td>
      </tr>
    </table>
    <p> こちらの内容で送信してもよろしいですか？</p>
    <!-- POSTの送信先はsend.phpであることに注意してください -->
    <form method="post" action="send.php">
      <input type="hidden" name="token" value="<?php echo $token ?>">
      <button type="submit" value="送信">送信</button>
      <a href="form.php?action=edit">戻る</a>
    </form>
  </div>
</body>

</html>
