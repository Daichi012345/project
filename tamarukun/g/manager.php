<?php
session_start(); // セッションを開始

$dsn = 'mysql:host=localhost;dbname=zenitamaru';
$db_user = 'root';
$db_password = 'root'; // ローカル開発環境のパスワードに適宜変更する


if (isset($_SESSION['user_ID'])) {
    $userID = $_SESSION['user_ID'];
    // $user_id を使った処理を続ける
} else {
    die("セッションにユーザーIDが設定されていません");
}

// 貯金金額と目標金額の初期化
$Savings_want = 'データが見つかりませんでした。';
$price_want = 'データが見つかりませんでした。';

try {
    // フォームが送信されたかどうかを確認
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // フォームデータを取得
        $thingName = isset($_POST['thingName']) ? $_POST['thingName'] : '';
        $price = isset($_POST['price']) ? $_POST['price'] : '';
        $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : date('Y-m-d'); // デフォルト値を設定
        $goalDate = isset($_POST['goalDate']) ? $_POST['goalDate'] : null;

       
        // PDO接続を作成する
        $PDO = new PDO($dsn, $db_user, $db_password);
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL クエリの準備
        $sql = "INSERT INTO want (userID, thingName, price, startDate, goalDate) VALUES (:userID, :thingName, :price, :startDate, :goalDate)";
        $stmt = $PDO->prepare($sql);

        // パラメータをバインドしてクエリを実行
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':thingName', $thingName, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':goalDate', $goalDate, PDO::PARAM_STR);
        if ($stmt->execute()) {
            echo "登録しました";
        } else {
            echo "登録に失敗しました";
        }
    }

} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>



<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/te.css">
    <link rel="stylesheet" href="./styles/header.css">
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> -->
    <title>たまる君</title>
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
                <li><img src="img/acount.png" alt="acount"><a href="./infochange.php">アカウント情報変更</a></li>
                <li class="admin-menu"><img src="img/list.jpg" alt="list"><a href="./manager.php">ほしいものリスト</a></li>
                <li class="admin-menu"><img src="img/gold.png" alt="貯金金額"><a href="./addmoney.php">貯金金額画面</a></li>
                <li class="admin-menu"><img src="img/home.png" alt="home"><a href="./main.php">達成状況状況確認</a> </li>
                <li class="admin-menu"><img src="img/shift.jpeg" alt="shift"><a href="./myshift.php">シフトボード</a></li>
                <li class="admin-menu"><a href="./anketto.php">貯まる君について</a></li>
                <li><img src="img/logout.jpg" alt="logout"><a href="./logout.php" id="logout">ログアウト</a></li>
            </ul>
        </div>
    </div>
    <main>
        <h1>欲しいものリスト</h1>
        <form id="registration-form" method="POST" action="manager.php">
            <div id="registrationDialog">
                <div class="thingName">
                    <input type="text" id='thingName' name="thingName" placeholder="欲しいものの名前">
                </div>
                <div class="price">
                    <input type="text" id='price' name="price" placeholder="金額" />
                </div>
                
                <div class="startDate">  
                    <input type="date" id=" startDate" name="startDate" placeholder="開始日">
                </div>
                <div class="goalDate">
                    <input type="date" id="goalDate" name="goalDate" placeholder="ゴール">
                </div>
            </div>
            <input type="submit" name="register" id="register" class="register" value="登録">
        </form>
    </main>
</body>
 <script src="./js/main.js"></script>

</html>