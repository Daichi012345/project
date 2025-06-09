<?php
session_start(); // Start or resume the session
if(isset($_GET['logout'])){

    // Unset all session variables
    $_SESSION = array();
    // Destroy the session
    session_destroy();
}
//<==-------------------logout user -----------------------===>

require_once "./def.php";

try{
   
    $DEPARTMENT = filter_input(INPUT_GET,"department",FILTER_VALIDATE_INT);
    //  echo $DEPARTMENT;
    $U_ID = null;

    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $db = new PDO($dsn, DB_USER, DB_PASS);
    //例外を発生させる
    $db -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    //静敵プレースホルダの設定
    $db -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
 //<==-----------------------logoin database -----------------------------===>


    if(isset($_GET['login'])){
        $U_ID = filter_input(INPUT_GET, "u_id");
        $U_PASSWORD = filter_input(INPUT_GET, "u_password");
        $sql = "SELECT * FROM employee WHERE U_ID = :u_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':u_id', $U_ID, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo '<pre>';
        // var_dump($user);
        // echo '<pre>';
        if ($user && $U_PASSWORD == $user['u_password']) {
            //session_start();
            $_SESSION['u_id'] = $user['u_id'];
            $_SESSION['u_name'] = $user['u_name'];
        } else {
            $U_ID = null;
            $message = "パスワードあらゆるユーザーが正しくありません。";
        }
    }
 //<==---------------------login user ------------------------------===>

    if (isset($_SESSION['u_id'])){
        $U_ID = $_SESSION['u_id'];
        $U_NAME = $_SESSION['u_name'];

        if(isset($_GET['sendBtn'])){
            if(isset($_GET['escape'])&& $_GET['escape'] == 1){
                $sqlUpdate = "UPDATE employee SET u_state = 2 WHERE u_id = :U_ID";
            }elseif(isset($_GET['escape'])&& $_GET['escape'] == 0){
                $sqlUpdate = "UPDATE employee SET u_state = 1 WHERE u_id = :U_ID";
            }
            else{
                $sqlUpdate = "UPDATE employee SET u_state = 0 WHERE u_id = :U_ID";
            }
            // echo $sqlUpdate;
            $stmt_update = $db -> prepare($sqlUpdate);
            $stmt_update->bindParam(':U_ID', $U_ID, PDO::PARAM_INT);
            $stmt_update -> execute();
            if(isset($_GET['escape'])&& $_GET['escape'] == 1){
                header("Location: damageDetails.php");
                exit();
            }
        }
        //<==---------------check, safe, damage and change status in database -------------------===>

        $sql = "SELECT u_id, u_password, u_name, D.d_dep AS u_department, u_location, u_state, u_date_time, u_comment
                FROM employee AS E
                JOIN department AS D ON E.d_id = D.d_id";

        if(isset($_POST['u_id'])){
            $user_id = $_POST['u_id'];
            
            // $where_u = " where u_id = ". "'".$user_id."'";
            // //echo $sql.$where_u;
            // $stmt1 = $db -> prepare($sql.$where_u);
            // $stmt1 ->execute();
            // $result_user = $stmt1->fetch(PDO::FETCH_ASSOC);
        }
        
        $where = " where true";
        if(isset($_GET['safe'])){
            $where .= ' and u_state = "1"';
        }elseif(isset($_GET['damage'])){
            $where .= ' and u_state = "2"';
        }
        switch($DEPARTMENT){
            case 1: $where .= " and E.d_id = '001'";break;
            case 2: $where .= " and E.d_id = '002'";break;
            case 3: $where .= " and E.d_id = '003'";break;
            case 4: $where .= " and E.d_id = '004'";break;
            case 5: $where .= " and E.d_id = '005'";break;
        }
        //echo $sql.$where_u;
        $stmt = $db -> prepare($sql.$where);
        //SQL の実行
        $stmt -> execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

     //<==------------------------data を表示する -----------------------------------------------===>

    }
    
// echo '<pre>';
// var_dump($result);
// echo '<pre>';

}catch(PDOException $poe){
    exit("DB erro".$poe -> getMessage());
}finally{
    $stmt = null;
    $db = null;
    $stmt1 = null;
    $stmt_update = null;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/jvm.css"> <!-- 全体のcss -->
    <link rel="stylesheet" href="./styles/list.css"> <!-- リストのcss -->
    <link rel="stylesheet" href="./styles/saftyDialog.css"> <!-- 安否情報入力のcss -->
    <link rel="stylesheet" href="./styles/loginDialog.css">
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> -->
    <title>安否確認掲示板</title>
</head>

<body>
    <header>
        <h1>安否確認掲示板</h1>
    </header>
    <?php if($U_ID):?>
    <main>
        
        <div id="Btns">
            <button id="editBtn">
                <img src="./images/hartNll.png">
                <p>安否情報</p>
            </button>
            <div id="userBtns">
                <button id="">
                    <img src="./images/checkedUser.png">
                    <p><?php echo $U_NAME;?></p>
                    <p id="small">さん</p>
                </button>
                <form method="get" action="" >
                <input type="submit" for="logout" name="logout" id="logoutBtn" value="ログアウト">
                </form>
            </div>
           
        </div>
        <form action="" method="get">
            <select name="department" id="select" value="全体">
                <option value="0">全体</option>
                <option value="1">総務</option>
                <option value="2">法務</option>
                <option value="3">人事</option>
                <option value="4">経理</option>
                <option value="5">広告</option>
            </select>
            <div id="tab-area">
                <input type="submit" for="tab1" name="whole" id="whole" value="全体">
                <input type="submit" for="tab2" name="safe" id="safe" value="安全">
                <input type="submit" for="tab3" name="damage" id="damage" value="被害あり">
            </div>
        </form>
        
        <!-- 大枠 -->
        <div id="tableField">
            <table id="itemList">
                <thead>
                    <tr>
                        <th>被害状況</th>
                        <th>名前</th>
                        <th>部署</th>
                        <th>時間</th>
                    </tr>
                </thead>
                <!-- ok -->
                <tbody>
                    <?php foreach ($result as $key): ?>
                        <?php if($key['u_state']==2): ?>
                            
                            <form action="" method="post">
                                <div>
                                    <tr class="touch" id="damage">
                                        <td class="status">
                                            <img src="./images/damage.png" alt="ロゴ" width="17">
                                            <button name="u_id" value="<?=$key['u_id']?>">
                                            被害あり
                                            </button>
                                        </td>
                                        <td><?=$key['u_name']?></td>
                                        <td><?=$key['u_department']?></td>
                                        <td><?=$key['u_date_time']?></td>
                                    </tr>
                                    <?php if(isset($user_id)&& $user_id == $key['u_id'] ): ?>
                                    <tr class="touch2" id="">
                                        <td colspan = 2>
                                            <p>場所：<?=$key['u_location']?></p>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr class="touch2" id="">
                                            <td colspan = 2>
                                                <p>被害情報：<?=$key['u_comment']?></p>
                                            </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php $ready = false ;?>
                                    <?php else:?>
                                    
                                    <?php endif;?>
                                </div>
                            </form>
                        <?php else:?>
                            <tr class="touch" id="<?php echo ($key['u_state'] == 1) ? 'noDamage' : 'offDamage' ?>">
                                <td class="status">
                                    <img src= "<?php echo ($key['u_state'] == 1) ? './images/noDamagepng.png' : './images/offHart.png"' ?>"  alt="ロゴ" width="17">
                                    <p><?php echo ($key['u_state'] == 1) ? '被害なし' : '未入力' ?></p>
                                </td>
                                <td><?=$key['u_name']?></td>
                                <td><?=$key['u_department']?></td>
                                <td><?=$key['u_date_time']?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- <textarea  name="box" id="box" cols="180" rows="20"  required></textarea> -->

    </main>
               <!-- 被害情報入力ダイアログ(ログイン後の画面に移行する！！) -->
            <dialog id="saftyDialog">
            <div id="saftyDialogWrap">
                <form id="safetyForm" method="get">
                    <div id="formTitle">
                        <p>被害情報</p>
                    </div>
                    <div class="contentDiv">
                        <p class="category">被害</p>
                        <label><input type="radio" name="hurt" value ="0" class="radio">なし</label>
                        <label><input type="radio" name="hurt" value ="1" class="radio">あり</label>
                    </div>
                    <div class="contentDiv">
                        <p class="category">避難</p>
                        <label><input type="radio" name="escape" value="0" class="radio">完了</label>
                        <label><input type="radio" name="escape" value="1" class="radio">未完了</label>
                    </div>
                    
                    <p class="attention">※被害あり、または避難未完了を選択した場合<br>
                        情報入力画面に進みます</p>
                    <input type="submit" name="sendBtn" id="sendBtn">
                </form>
            </div>
            <span class="material-icons">X</span>
        </dialog>
        <?php else:?>
        <main>
        <div>
            <button id="loginBtn">
                <img src="./images/human.png">
                <p>ログイン</p>
                <p>▼</p>
            </button>
        </div>
        <button id="editBtn">
            <img src="./images/hartNll.png">
            <p>安否情報</p>
        </button>
        <div id="tab-area">
            <input type="radio" id="tab1" name="tab" checked />
            <label for="tab1">全体</label>
            <input type="radio" id="tab2" name="tab" />
            <label for="tab2">安全</label>
            <input type="radio" id="tab3" name="tab" />
            <label for="tab3">被害あり</label>
            <div class="tab-box">
                <div id="tab-content1">コンテンツ1の内容</div>
                <div id="tab-content2">コンテンツ2の内容</div>
                <div id="tab-content3">コンテンツ3の内容</div>
        </div>
    </main>

    <!-- ログインダイアログ -->
    <dialog id="loginDialog">
        <form id="login-form" method="get">
            <div id="loginDialogWrap">
                    <div id="loginformTitle">
                        <h2>LOGIN</h2>
                    </div>
                 
                    <div class="formField">
                        <img src="./images/human.png" alt="Human" width="40" height="45">
                        <input type="text" id='id' name="u_id" placeholder="ユーザーID"  />
                    </div>
                    <div class="formField">
                        <img src="./images/kye.png" alt="key" width="45" height="45">
                        <input type="password" id='pass' name="u_password" placeholder="パスワード"  />
                    </div>
                    <input type="submit" name="login" id="login" value="ログイン">
                    <a href="" id="register">登録</a>
            </div>
        </form>
        <span class="material-icons">X</span>
    </dialog>

    <dialog id="registerDialog">
        <form id="register-form" method="get">
            <div id="registerDialogWrap">
                    <div id="registerformTitle">
                        <h2>登録</h2>
                    </div>
              
                    <div class="formField">
                        <img src="./images/human.png" alt="Human" width="40" height="45">
                        <input type="text" id='id' name="u_id" placeholder="ユーザーID"  />
                    </div>
                    <div class="formField">
                        <img src="./images/kye.png" alt="key" width="45" height="45">
                        <input type="password" id='pass' name="u_password" placeholder="パスワード"  />
                    </div>
                    <input type="submit" name="register" id="register" value="登録">
                    
                    <a href="" id="login">ログイン</a>
            </div>
        </form>
        <span class="material-icons">X</span>
    </dialog>
        <?php endif;?>
    
    <script src="./scripts/beforLogin.js"></script>
    

</body>

</html>