<?php
session_start();

require_once "./def.php";

// if(isset($_GET['Btn'])){
//     echo $_GET['commet'];
//     echo $_GET['address'];
// }

try{
    $ready = true;
    $DEPARTMENT = filter_input(INPUT_GET,"department",FILTER_VALIDATE_INT);
    //  echo $DEPARTMENT;
    $U_ID = null;
    $U_LOCATION = null;
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $db = new PDO($dsn, DB_USER, DB_PASS);
    //例外を発生させる
    $db -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    //静敵プレースホルダの設定
    $db -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
 //<==-----------------------logoin database -----------------------------===>

    if(isset($_SESSION['u_id'])){
        $U_ID = $_SESSION['u_id'];
        $sql = "SELECT * FROM  employee WHERE u_id = :U_ID";
        $stmt = $db -> prepare($sql);
        $stmt->bindParam(':U_ID', $U_ID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $U_LOCATION = $result['u_location'];


        $sqlUpdate = "UPDATE employee SET";
        if(isset($_GET['changeBtn'])){
            $U_LOCATION =  $_GET['address'];
            $set_location = " u_location = :U_LOCATION";
            $where = " WHERE u_id = :U_ID";
            $stmt_update = $db -> prepare($sqlUpdate.$set_location.$where);
            $stmt_update->bindParam(':U_ID', $U_ID, PDO::PARAM_INT);
            $stmt_update->bindParam(':U_LOCATION', $U_LOCATION, PDO::PARAM_INT);
            $stmt_update -> execute();

        }
        if(isset($_GET['Btn'])){
            $U_COMMENT = $_GET['comment'];
            $set_comment = " u_comment = :U_COMMENT";
            $where = " WHERE u_id = :U_ID";
            $stmt_update = $db -> prepare($sqlUpdate.$set_comment.$where);
            $stmt_update->bindParam(':U_ID', $U_ID, PDO::PARAM_INT);
            $stmt_update->bindParam(':U_COMMENT', $U_COMMENT, PDO::PARAM_INT);
            $stmt_update -> execute();
            header("Location: JVM_team.php");
            exit();
        }
    }
}catch(PDOException $poe){
    exit("DB erro".$poe -> getMessage());
}finally{
    $stmt = null;
    $db = null;
    $stmt_update = null;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./styles/damageDetails.css">
    <title>Document</title>
</head>
<body>
        <header>
            <h1>安否確認掲示板</h1>
        </header>
        <button id = "test"> get </button>
        <main>
                <div class="contentHeader">
                    <div class="backBtn">
                        <a href="JVM_team.php"><img src="images/backBtn.png" alt="ロゴ" width="30"></a>
                    </div>
                    <h1 class="taitle">被害情報の入力</h1>
            <form action="" method="get">
                </div>
                
                    <div class="contentDiv">
                        <?php if(isset($_GET['editBtn'])) :?>
                            <label for="address" class="contents">現在地：</label>
                            <input type="text" name="address" id="location" placeholder="<?=$U_LOCATION?>" class="contents">
                            
                            <input name="changeBtn" type="submit" value="変更" class="editBtn" />
                        <?php else:?>
                            <p class="contents">現在地：</p>
                            <p name="place" class="contentValue"> <?php echo $U_LOCATION ;?></p>
                            <input name="editBtn" type="submit" value="編集" class="editBtn" id ="test" />
                            
                        <?php endif;?>
                    </div>
                
                <hr class="line">
                <div class="contentDiv">
                    <p class="contents">被害状況：</p>
                    <!-- <input type="submit" value="編集" class="stateBtn" /> -->
                </div>
                
                    <!-- <input name="commet" type="text" class="commet" /> -->
                    <textarea name="comment"  class="comment"></textarea>
                    <input name="Btn" type="submit" id="Btn" value="送信"/>
            </form>
        </main>
        

        <script src="./scripts/GPS_test.js"></script>
        <script src="https://maps.gsi.go.jp/js/muni.js"></script>
</body>
</html>