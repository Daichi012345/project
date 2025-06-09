<?php
session_start(); // Bắt đầu session

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_ID'])) {
    header("Location: ./login.php"); // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Khởi tạo biến $feedbacks dưới dạng mảng rỗng
$feedbacks = [];

$dsn = 'mysql:host=localhost;dbname=zenitamaru';
$db_user = 'root';
$db_password = "root";

try {
    // PDOオブジェクトを作成
    $PDO = new PDO($dsn, $db_user, $db_password);
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // // Truy xuất dữ liệu từ bảng feedback
    // $sql = "SELECT * FROM feedback WHERE userID = :userID";
    // $stmt = $PDO->prepare($sql);
    // $stmt->execute(array(':userID' => $_SESSION['user_ID']));

// Truy xuất dữ liệu từ bảng feedback
$sql = "SELECT * FROM feedback "; // Lấy tất cả các bình luận
$stmt = $PDO->prepare($sql);
$stmt->execute();

// Lấy tất cả các hàng dữ liệu
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Biến để lưu tổng số rating và số lượng đánh giá
$totalRating = 0;
$ratingCount = 0;

foreach ($feedbacks as $feedback) {
    $totalRating += intval($feedback['rating']);
    $ratingCount++;
}

// Tính trung bình rating
$averageRating = 0;
if ($ratingCount > 0) {
    $averageRating = round($totalRating / $ratingCount);
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
    <link rel="stylesheet" href="./styles/feedback.css">
    <link rel="stylesheet" href="./styles/header.css">
    
    <title>Feedback</title>
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
        <div class="main-contents">
            <div class="content">
                <h1 class="center">フィードバック一覧表</h1>
                <p class="container-top">ご感想あります。あなたの意見めっちゃ助けています<br></p>
                <!-- Hiển thị trung bình rating -->
                <p class="average-rating">
                    平均評価: 
                    <?php
                    for ($i = 0; $i < 5; $i++) {
                        if ($i < $averageRating) {
                            echo '<span class="star filled">★</span>';
                        } else {
                            echo '<span class="star">☆</span>';
                        }
                    }
                    ?> / 5
                </p>

                <hr>
                <div class="feedback-list">

                    <?php if (!empty($feedbacks)): ?>
                        <?php foreach ($feedbacks as $feedback): ?>
                            <div class="feedback-item">
                            <div class="input-container">
                                <h2>フィードバックID: <?= htmlspecialchars($feedback['feedbackID']) ?></h2>
                                <div class="input-container" id="q1_div">
                                <div id="other_message" class="other_message">
                                    <label for="comment1">1.ウェブサイトどう思いますか？あなたに役立っていますか？<br></label>
                                    <textarea id="comment1" name="comment1" value=""><?= nl2br(htmlspecialchars($feedback['comment1'])) ?></textarea>
                                </div>

                                <div id="other_message" class="other_message">
                                    <label for="comment2">2.ウェブサイトのデザインはどうですか？<br></label>
                                    <textarea id="comment2" name="comment2" value=""><?= nl2br(htmlspecialchars($feedback['comment2'])) ?></textarea>
                                </div>
                                <div id="other_message" class="other_message">
                                <label for="comment3">3.ウェブサイトこの機能が追加したら良いと思う（追加したい機能入力）<br></label>
                                <textarea id="comment3" name="comment3" value=""> <?= nl2br(htmlspecialchars($feedback['comment3'])) ?></textarea>
                                </div>
                                <div id="other_message" class="other_message">
                                <label for="comment4">4．その他 <br></label>
                                <textarea id="comment4" name="comment4" value=""><?= nl2br(htmlspecialchars($feedback['comment4'])) ?></textarea>
                                </div>
                                <div class="other_message">
                                <label for="status_message">5.ウェブサイト何点付けますか？<br></label>
                               
                                <p>
                                <?php
                                // Chuyển đổi rating từ số sang dấu sao
                                $stars = intval($feedback['rating']);
                                for ($i = 0; $i < 5; $i++) {
                                    if ($i < $stars) {
                                        echo '<span class="star filled">★</span>';
                                    } else {
                                        echo '<span class="star">☆</span>';
                                    }
                                }
                                ?>
                            </p>


                                
                                
                                </div>
                                <button type="submit">Gửi</button>
            

                            </div>
                                <!-- from cu duoc luu o phan addmoney().php -->
                                <hr>  
                        <?php endforeach; ?>
                        
                    <?php else: ?>
                        <p>まだフィードバックがありません。</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <script src="./js/main.js"></script>
</body>
</html>


