<?php
session_start(); // セッションを開始

$dsn = 'mysql:host=localhost;dbname=zenitamaru';
$db_user = 'root';
$db_password = 'root';

if (isset($_SESSION['user_ID'])) {
    $userID = $_SESSION['user_ID'];
} else {
    die("セッションにユーザーIDが設定されていません");
}

try {
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 貯金金額の値抽出
    $Savings_stmt = $pdo->prepare('SELECT status FROM want WHERE userID = :userID LIMIT 1');
    $Savings_stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $Savings_stmt->execute();
    $Savings = $Savings_stmt->fetch(PDO::FETCH_ASSOC);

    // 目標金額の値抽出
    $price_stmt = $pdo->prepare('SELECT price FROM want WHERE userID = :userID LIMIT 1');
    $price_stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $price_stmt->execute();
    $price = $price_stmt->fetch(PDO::FETCH_ASSOC);

    $Savings_want = $Savings ? $Savings['status'] : 'データが見つかりませんでした。';
    $price_want = $price ? $price['price'] : 'データが見つかりませんでした。';

} catch (PDOException $e) {
    echo 'DB接続失敗: ' . $e->getMessage();
    $Savings_want = 'DB接続失敗';
    $price_want = 'DB接続失敗';
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/main.css">
    <link rel="stylesheet" href="./styles/header.css">
    <title>達成率状況確認</title>
</head>

<body>
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
    <main>
        <h1>達成率状況確認</h1>
        <div class="SavingsStatus">
            <h2>あなたの現在の貯金状況です</h2>
        </div>
        <div>

        </div>
        <!-- 目標金額表示 -->
        <div class="h2">
            <h2>目標金額</h2>
            <div class="echo">
                <?php if ($Savings_want >= $price_want) {
                    echo '目標を達成しました！！<br>おめでとうございます！！！';
                } else {
                    echo htmlspecialchars($price_want, ENT_QUOTES, 'UTF-8');
                }
                ?>
            </div>
        </div>
        <!-- 貯金金額表示 -->
        <div class="saving_want">
            <div class="h2">
                <h2>貯金金額</h2>
                <div class="echo">
                    <p><?php echo htmlspecialchars($Savings_want, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
        </div>
    </main>
</body>
<script src="./js/main.js"></script>


</html>