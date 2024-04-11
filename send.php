<?php
session_start(); //セッションを使用するのでスタートさせます

if ($_SESSION['token'] === $_POST['token']) { // #1
  if (isset($_SESSION['kinds'])) {  // #2
    $kinds = $_SESSION['kinds'];
    $name = $_SESSION['name'];
    $email = str_replace(array("\r", "\n"), '', $_SESSION['email']);
    $title = $_SESSION['title'];
    $body = $_SESSION['body'];
  } // #2

  // 自分に送るお問い合わせ内容メールを構築
  $to = "sample@sample.com";
  $mailtitle = "{$name}様よりお問い合わせが届きました。";
  $contents = <<<EOD

              ◆種別
              {$kinds}

              ◆お名前
              {$name}

              ◆メールアドレス
              {$email}

              ◆件名
              {$title}

              ◆内容
              {$body}

              EOD;
  $from = "Return-Path: " . $email . "\r\n";
  $from = $from . 'From: ' . $email; //送信元メールアドレス
  // 自分に送るお問い合わせ内容メールを構築

  // 相手に送る送信完了メールを構築
  $to2 = $email;
  $mailtitle2 = "【自動送信】受付を完了いたしました。";
  $contents2 = <<<EOD
              お問い合わせありがとうございます。
              以下の内容を送信いたしました。
              必ず返信いたしますのでしばらくお待ちください。
              ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                
              {$contents}

              ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
              E-mail: sample@sample.com
              サイト運営者：あめ

              EOD;
  $from2 = "Return-Path:" . $to . "\r\n";
  $from2 =  $from2 . 'From: ' . $to;
  // 相手に送る送信完了メールを構築

  // メールを送るときのおまじない
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");

  $param = "-f" . $to;
  //  mb_send_mail(送信先,タイトル,本文,追加ヘッダ,追加コマンドラインパラメータ)
  if (mb_send_mail($to2, $mailtitle2, $contents2, $from2, $param)) { // 相手に送信 // #3

    $message = '<p class="question-text">『' . $email . '』宛に確認メールを送信しました<br>お問い合わせありがとうございます。</p>';

    if (mb_send_mail($to,$mailtitle,$contents,$from,$param)) { // 自分に送信 // #4

      // 終了処理開始 セッションの破棄
      $_SESSION = [];
      if (isset($_COOKIE[session_name()])) {  // #5
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params['httponly']);
      }
      session_destroy();
      // セッションの破棄
    } else { // #4
      $message = '<p class="question-text error">何らかの理由で送信エラーが発生しました<br>しばらく待ってから再度送信してください</p>';
    } // #4
  } else { // #3
    $message = '<p class="question-text error">『' . $email . '』宛に確認メールを送信できませんでした。<br>正しいメールアドレスで再度ご連絡をお願いいたします。</p>';
  } // #3
} else {  // #1
  // 直接send.phpにアクセスしようとしたら強制的にリダイレクト
  header('Location:http://localhost/Questionform/form.php');
  // header('Location:http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/form.php');
}  // #1
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
  <?php
  if ($message !== "") {
    echo $message;
  }
  ?>
  <a href="form.php">TOPに戻る</a>
</body>

</html>
