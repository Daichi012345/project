// 要素の取得
const saftyDialogOpen = document.querySelector('#editBtn');         // 安否情報入力ボタン
const saftyDialog = document.querySelector('#saftyDialog');         // ダイアログ

const sendBtn = document.querySelector('#sendBtn');                 // 送信ボタン
const loginBtn = document.querySelector('#loginBtn');                  // ログインボタン
//console(loginBtn);
const loginDialog = document.querySelector('#loginDialog');          // ログインダイアログ
const registerDialog = document.querySelector('#registerDialog');

const itemList = document.querySelector('#itemList');


// イベント --------------------------------------------------------------------------------------


if(loginDialog || registerDialog){
    const loginCloseBtn = loginDialog.querySelector('.material-icons');
    const register = loginDialog.querySelector('#register');
    const login = registerDialog.querySelector('#login')
    //console.log(register);
    
    //ログインボタンを押した時の処理
    loginBtn.addEventListener('click', (event) => {
        event.preventDefault();
        loginDialog.showModal();
    });
    //✕でダイアログを閉じる
    loginDialog.addEventListener('click', (event) => {
        if(event.target == loginCloseBtn|| event.target == loginDialog){
            loginDialog.close();
        }
    });
    loginDialog.addEventListener('click', (event) => {
        if(event.target == register){
            loginDialog.close();
            event.preventDefault();
            registerDialog.showModal();
        }
    });
    registerDialog.addEventListener('click', (event) => {
        if(event.target == login){
            registerDialog.close();
            event.preventDefault();
            loginDialog.showModal();
        }
    });
}

if(saftyDialog){
    const saftyCloseBtn = saftyDialog.querySelector('.material-icons');

    saftyDialogOpen.addEventListener('click', (event) => {
         event.preventDefault();
         saftyDialog.showModal();
     });
    saftyDialog.addEventListener('click', (event) => {
        if(event.target == saftyCloseBtn|| event.target == saftyDialog){
            saftyDialog.close();
        }
    })
}




// (ログイン後の画面に移行する！！) ------------------------
// 被害情報入力ダイアログを開く



// 送信ボタンを押した時の処理
// sendBtn.addEventListener('click', (event) => {
//     saftyDialog.close();
// })
