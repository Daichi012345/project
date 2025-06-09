// ----main　ページに移動操作　ここまでーーーーーーーーーーーー

const headerMenu = document.querySelector(".headerMenu");
const menuBtn = document.querySelector(".hamburgerMenu");

document.addEventListener("click", (e) => {
  let eParent = e.target.closest(".hamburgerMenu"); // 親要素にhamburgerMenuクラスの要素があれば代入する
  if (e.target == menuBtn || eParent == menuBtn) {
    // クリックされた場所がhamburgerMenuクラスのタグ範囲であれば動く
    if (headerMenu.classList.contains("menuOpen")) {
      // 開いているときTrue
      headerMenu.classList.remove("menuOpen"); // 閉じる
    } else {
      headerMenu.classList.add("menuOpen"); // 開く
    }
  } else if (!e.target.closest(".menuList")) {
    // クリックされた場所がhamburgerMenuクラスとmenuListクラスのどちらでもないとき
    if (headerMenu.classList.contains("menuOpen")) {
      headerMenu.classList.remove("menuOpen"); // 開いていた際に閉じるだけ
    }
  }
});