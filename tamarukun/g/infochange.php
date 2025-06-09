<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=zenitamaru';
$db_user = 'root';
$db_password = 'root';

if (!isset($_SESSION['user_ID'])) {
    die("セッションにユーザーIDが設定されていません");
}

$userID = $_SESSION['user_ID'];


try {
    $db = new PDO($dsn, $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $query = "SELECT accountName, email FROM USER WHERE userID = :userID LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("ユーザーが見つかりません");
    }

    $accountName = $user['accountName'];
    $email = $user['email'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["infoChange"])) {
        $accountName = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $mail = filter_input(INPUT_POST, "mail", FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, "password");
        $password2 = filter_input(INPUT_POST, "password2");
    
        $result = [
            "status" => true,
            "result" => null,
            "mail" => $mail,
            "accountName" => $accountName,
            "password" => $password,
            "password2" => $password2,
        ];
    
        if (!$mail || !$accountName || !$password || !$password2) {
            $result["status"] = false;
        }
    
        if ($result["status"] && $password === $password2) {
            try {
                $db->beginTransaction();
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sqlUpdate = "UPDATE USER SET accountName = :accountName, email = :mail, password = :password WHERE userID = :userID";
                $stmt = $db->prepare($sqlUpdate);
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $stmt->bindParam(':accountName', $accountName, PDO::PARAM_STR);
                $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmt->execute();
                $result["result"] = $stmt->rowCount();
    
                if ($result["result"] === 1) {
                    $_SESSION['email'] = $mail;
                    $db->commit();
                    header("Location: myshift.php");
                    exit;
                } else {
                    $db->rollBack();
                }
            } catch (PDOException $poe) {
                $db->rollBack();
                exit("DBエラー: " . $poe->getMessage());
            }
        } else {
            echo "エラー: パスワードが一致しないか、必須項目が未入力です。";
        }
    }
    

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["infoChange"])) {
        // POSTデータを確認
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        $accountName = filter_input(INPUT_POST, "accountName", FILTER_SANITIZE_SPECIAL_CHARS);
        $mail = filter_input(INPUT_POST, "mail", FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, "password");
        $password2 = filter_input(INPUT_POST, "password2");

        $result = [
            "status" => true,
            "result" => null,
            "mail" => $mail,
            "accountName" => $accountName,
            "password" => $password,
            "password2" => $password2,
        ];

        if (!$mail) {
            $result["status"] = false;
        }
        if (!$accountName) {
            $result["status"] = false;
        }
        if (!$password) {
            $result["status"] = false;
        }
        if (!$password2) {
            $result["status"] = false;
        }

        if ($result["status"] && $password === $password2) {
            try {
                $db->beginTransaction();
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sqlUpdate = "UPDATE USER SET accountName = :accountName, email = :mail, password = :password WHERE userID = :userID";
                $stmt = $db->prepare($sqlUpdate);
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $stmt->bindParam(':accountName', $accountName, PDO::PARAM_STR);
                $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmt->execute();
                $result["result"] = $stmt->rowCount();

                // データベース更新結果を表示
                echo "<pre>";
                print_r($result);
                echo "</pre>";

                if ($result["result"] === 1) {
                    $_SESSION['email'] = $mail;
                    $db->commit();
                    header("Location: myshift.php");
                    exit;
                } else {
                    $db->rollBack();
                    $result["status"] = false;
                }
            } catch (PDOException $poe) {
                $db->rollBack();
                exit("DBエラー: " . $poe->getMessage());
            }
        }
    }
} catch (PDOException $e) {
    exit("DB接続エラー: " . $e->getMessage());
}

$stmt = null;
$db = null;
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/header.css">
    <link rel="stylesheet" href="./styles/common.css?ver=2.3.5">
    <title>infochange</title>
</head>

<body>
  <header>
    <h1>貯まる君</h1>
    <div class="headerMenu">
      <div class="menuIcon">
        <div class="hamburgerMenu">
          <div class="hamburgerBar hb1"></div>
          <div class="hamburgerBar hb2"></div>
          <div class="hamburgerBar hb3"></div>
        </div>
      </div>
      <div class="menuList">
            <ul>
                <li><img src="img/login.jpg" alt="login"><a href="./login.php">ログイン</a></li>
                <li><img src="img/acount.png" alt="account"><a href="./infochange.php">アカウント情報変更</a></li>
                <li class="admin-menu"><img src="img/list.jpg" alt="list"><a href="./manager.php">ほしいものリスト</a></li>
                <li class="admin-menu"><img src="img/gold.png" alt="貯金金額"><a href="./addmoney.php">貯金金額画面</a></li>
                <li class="admin-menu"><img src="img/home.png" alt="home"><a href="./main.php">達成状況状況確認</a></li>
                <li class="admin-menu"><img src="img/shift.jpeg" alt="shift"><a href="./myshift.php">シフトボード</a></li>
                <li class="admin-menu"><a href="./anketto.php">貯まる君について</a></li>
                <li><img src="img/logout.jpg" alt="logout"><a href="./logout.php" id="logout">ログアウト</a></li>
            </ul>
        </div>
    </div>
    </div>
  </header>
   
    <main>
        <div class="main-contents">
            <div class="content">
                <h1 class="center">情報変更<?=$userID?></h1>
                <hr>
                <form id="registrationForm" method="POST">
                    <div class="wrap-box flex-even">
                        <div class="center input-item">
                            <label for="memberId">
                                <h2>ACOUNT NAME</h2>
                            </label>
                            <div class="input-group">
                                <img src="img/human.jpeg" alt="人ロゴ">
                                <input type="text" id="username" name="username" placeholder="アカウント名を入力してください" required>
                            </div>
                        </div>
                        <div class="center input-item">
                            <label for="memberId">
                                <h2>MAIL</h2>
                            </label>
                            <div class="input-group">
                                <img src="img/mail.png" alt="メールロゴ">
                                <input type="text" id="mail" name="mail" placeholder="メールを入力してください" required>
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
                            <label for="password">
                                <h2>パスワード（再）</h2>
                            </label>
                            <div class="input-group">
                                <img src="img/key.png" alt="鍵ロゴ">
                                <input type="password" id="password" name="password2" placeholder="パスワードを入力してください" required>
                            </div>
                        </div>
                        <div class="submit">
                            <button type="submit" id="submit" name="infoChange">変更</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <!-- <script>
        // document.getElementById('registrationForm').addEventListener('submit', function(event) {
        //     event.preventDefault();
        //     window.location.href = 'login.html'; // Thay đổi thành trang bạn muốn chuyển hướng tới
        // });
    </script> -->
    <script src="./js/main.js"></script>
</body>
</html>