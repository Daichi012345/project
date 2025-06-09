<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dsn = 'mysql:host=localhost;dbname=zenitamaru';
$db_user = 'root';
$db_password = "root";

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $accountName = $_POST['accountName'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password-confirm'];
        $email = $_POST['email'];

        // パスワードが一致しているか確認
        if ($password !== $password_confirm) {
            throw new Exception("パスワードが一致しません");
        }

        // パスワードをハッシュ化
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // PDOオブジェクトを作成する
        $PDO = new PDO($dsn, $db_user, $db_password);
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL クエリの実行確認
        $sql = "INSERT INTO user (name, email, password, accountName) VALUES (:name, :email, :password, :accountName)";
        $stmt = $PDO->prepare($sql);

        // 準備ステートメントが成功したか確認
        if ($stmt === false) {
            throw new Exception("クエリの準備に失敗しました");
        }

        $params = array(':name' => $name, ':email' => $email, ':password' => $hashed_password, ':accountName' => $accountName);

        // SQL クエリの実行結果の確認
        if ($stmt->execute($params)) {
            // 挿入されたIDを取得
            $lastInsertId = $PDO->lastInsertId();
            echo "アカウントが作成されました。<br>";
            echo "ユーザーID: " . htmlspecialchars($lastInsertId, ENT_QUOTES, 'UTF-8') . "<br>";
            header("Location: ./login.php"); 
        } else {
            echo "アカウントの作成に失敗しました。";
            var_dump($stmt->errorInfo());
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
    <link rel="stylesheet" href="./styles/adduser.css">
    <title>新規登録</title>
</head>
<body>
<header>
<h1>貯まる君</h1>
</header>
    <main>
        <div class="main-contents">
            <div class="content">
                <h1 class="center">新規登録</h1>
                <hr>
                <form method="post">
                    <div class="wrap-box flex-even">
                        <div class="center input-item">
                            <label for="name">
                                <h2>名前</h2>
                            </label>
                            <div class="input-group">
                                <img src="img/human.jpeg" alt="人ロゴ">
                                <input type="text" id="name" name="name" placeholder="名前を入力してください" required>
                            </div>
                        </div>
                        <div class="center input-item">
                            <label for="accountName">
                                <h2>アカウント名</h2>
                            </label>
                            <div class="input-group">
                                <img src="img/human.jpeg" alt="人ロゴ">
                                <input type="text" id="accountName" name="accountName" placeholder="アカウント名を入力してください" required>
                            </div>
                        </div>
                        <div class="center input-item">
                            <label for="email">
                                <h2>メールアドレス</h2>
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
                        <div class="center input-item">
                            <label for="password-confirm">
                                <h2>パスワード(再)</h2>
                            </label>
                            <div class="input-group">
                                <img src="img/key.png" alt="鍵ロゴ">
                                <input type="password" id="password-confirm" name="password-confirm" placeholder="パスワードを再度入力してください" required>
                            </div>
                        </div>
                        <div class="submit">
                            <input type="submit" id="submit" value="登録">
                        </div>
                    </div>
                </form>
                <a href="login.php" id="register">ログイン</a>
            </div>
        </div>
    </main>
</body>
</html>
