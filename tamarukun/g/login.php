<?php
session_start(); // セッションを開始

$dsn = 'mysql:host=localhost;dbname=zenitamaru';
$db_user = 'root';
$db_password = "root";


try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // PDOオブジェクトを作成
        $PDO = new PDO($dsn, $db_user, $db_password);
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ユーザーを取得するクエリを準備
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $PDO->prepare($sql);
        $stmt->execute(array(':email' => $email));

        // ユーザーが存在するかチェック
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // パスワードの照合
            if (password_verify($password, $user['password'])) {
                // ログイン成功時の処理
                $_SESSION['user_ID'] = $user['userID']; // ユーザーIDをセッションに保存
                $_SESSION['user_name'] = $user['name']; // ユーザー名をセッションに保存
               
                if ($user['is_first_login']) {
                  // 初回ログインの場合
                  $updateSql = "UPDATE user SET is_first_login = 0 WHERE email = :email";
                  $updateStmt = $PDO->prepare($updateSql);
                  $updateStmt->execute(array(':email' => $email));

                  header("Location: ./manager.php");
              } else {
                  // 2回目以降のログインの場合
                  header("Location: ./main.php");
              }
              exit();
            } else {
                throw new Exception("パスワードが正しくありません");
            }
        } else {
            throw new Exception("指定されたメールアドレスのユーザーが見つかりません");
        }
    }
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage();
}


?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/common.css">
  <title>login</title>
</head>
<header>
    <h1>貯まる君</h1>
 </header>
<body>
  <main>
    <div class="main-contents">
      <div class="content">
        <h1 class="center">アカウントログイン</h1>
        <hr>
        <form method="post">
          <div class="wrap-box flex-even">
            <div class="center input-item">
              <label for="memberId">
                  <h2>MALL</h2>
              </label>
              <div class="input-group">
                  <img src="img/mail.png" alt="メールロゴ">
                  <input type="email" id="email" name="email" placeholder="メールアドレスを入力してください" required>
              </div>
          </div>
            <div class="center input-item">
              <label for="password">
                <h2>パスワード</h2>
              </label>
              <div class="input-group">
                <img src="img/key.png" alt="鍵ロゴ">
                <input type="password" id="password" name="password" placeholder="パスワードを入力してください" required>
              </div>

            </div>
            <div class="submit">
              <input type="submit" id="submit" value="ログイン">
            </div>
            <div><a href="./adduser.php">新規登録はこちら</a></div>
          </div>
        </form>
      </div>
    </div>
    
  </main>
  <script>
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        event.preventDefault();
        window.location.href = './style/main.html'; // Thay đổi thành trang bạn muốn chuyển hướng tới
    });
</script>
</body>

</html>