<?php
session_start(); // セッションを開始

// ログイン確認
if (!isset($_SESSION['user_ID'])) {
    header("Location: ./login.php"); // ログインまだ場合、ログイン画面へ
    exit();
}

// セクションから利用者情報取得
$user_ID = $_SESSION['user_ID'];
$dsn = 'mysql:host=localhost;dbname=zenitamaru';
$db_user = 'root';
$db_password = "root";

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";

        $comment1 = $_POST['comment1'];
        $comment2 = $_POST['comment2'];
        $comment3 = $_POST['comment3'];
        $comment4 = $_POST['comment4'];
        $rating = isset($_POST['rating']) ? $_POST['rating'] : '0';

        // Kiểm tra user_ID từ session
        // echo "user_ID from session: " . $user_ID;
        // echo "<br>Rating: " . $rating;
        // PDOオブジェクトを作成
        $PDO = new PDO($dsn, $db_user, $db_password);
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // フィードバックをデータベースに挿入するSQLクエリを準備
        $sql = "INSERT INTO feedback (userID, comment1, comment2, comment3, comment4, rating) 
                VALUES (:userID, :comment1, :comment2, :comment3, :comment4, :rating)";
        $stmt = $PDO->prepare($sql);
        $stmt->execute(array(
            ':userID' => $user_ID,
            ':comment1' => $comment1,
            ':comment2' => $comment2,
            ':comment3' => $comment3,
            ':comment4' => $comment4,
            ':rating' => $rating
        ));

        echo "フィードバックが正常に保存されました。";
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
    <link rel="stylesheet" href="./styles/header.css">
    <link rel="stylesheet" href="./styles/anketto.css">
    <title>anketto</title>
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
                <li><img src="img/acount.png" alt="acount"><a href="./infochange.php">アカウント情報変更</a></li>
                <li class="admin-menu"><img src="img/list.jpg" alt="list"><a href="./manager.php">ほしいものリスト</a></li>
                <li class="admin-menu"><img src="img/gold.png" alt="貯金金額"><a href="./addmoney.php">貯金金額画面</a></li>
                <li class="admin-menu"><img src="img/home.png" alt="home"><a href="./main.php">達成状況状況確認</a> </li>
                <li class="admin-menu"><img src="img/shift.jpeg" alt="shift"><a href="./myshift.php">シフトボード</a></li>
                <li class="admin-menu"><a href="./anketto.php">貯まる君について</a></li>
                <li class="admin-menu"><a href="./feedback.php">評価について</a></li>
                <li><img src="img/logout.jpg" alt="logout"><a href="./logout.php" id="logout">ログアウト</a></li>
        </ul>
      </div>
    </div>

    </header>
    <main>
        <div class="container">
            <div class="container-head center">
                <h2>サイトの感想、アンケート</h2>
            </div>
            <div>
                <p class="container-top">ウェブサイトお使い頂き、誠にありがとうございます。<br>
                    ウェブサイトを皆さんにもっと使いやすいために、皆さんのご意見をお聞かせください。<br>
                    ご協力ありがとうございます！<br></p>
            </div>
            <form id="registrationForm" method="POST">
                <div class="input-container" id="q1_div">
                    <div id="other_message" class="other_message">
                        <label for="comment1">1.ウェブサイトどう思いますか？あなたに役立っていますか？<br></label>
                        <textarea id="comment1" name="comment1"></textarea>
                    </div>
                    <div id="other_message" class="other_message">
                        <label for="comment2">2.ウェブサイトのデザインはどうですか？<br></label>
                        <textarea id="comment2" name="comment2"></textarea>
                    </div>
                    <div id="other_message" class="other_message">
                        <label for="comment3">3.ウェブサイトこの機能が追加したら良いと思う（追加したい機能入力）<br></label>
                        <textarea id="comment3" name="comment3"></textarea>
                    </div>
                    <div id="other_message" class="other_message">
                        <label for="comment4">4．その他 <br></label>
                        <textarea id="comment4" name="comment4"></textarea>
                    </div>
                    <div class="other_message">
                        <label for="status_message">5.ウェブサイト何点付けますか？<br></label>
                        <div id="rating">
                            <input type="radio" id="star5" name="rating" value="5" />
                            <label class="full" for="star5" title="Awesome - 5 stars"></label>

                            <input type="radio" id="star4" name="rating" value="4" />
                            <label class="full" for="star4" title="Pretty good - 4 stars"></label>

                            <input type="radio" id="star3" name="rating" value="3" />
                            <label class="full" for="star3" title="Meh - 3 stars"></label>

                            <input type="radio" id="star2" name="rating" value="2" />
                            <label class="full" for="star2" title="Kinda bad - 2 stars"></label>

                            <input type="radio" id="star1" name="rating" value="1" />
                            <label class="full" for="star1" title="Sucks big time - 1 star"></label>
                        </div>
                    </div>
                    <div class="submit center">
                        <input type="submit" value="送信">
                    </div>
                </div>
            </form>
        </div>
    </main>
    <script src="./js/main.js"></script>
    <!-- <script>
        document.getElementById('registrationForm').addEventListener('submit', function (event) {
    event.preventDefault();
    window.location.href = './feedback.php'; // Thay đổi thành trang bạn muốn chuyển hướng tới
    });
    </script> -->
</body>
</html>
