$(function() {
  //メニュー画面表示
  function addTask() {

    $(document).on('click', '.menu-btn, #menu-close, #menu-modal', function (e) {
      //<a></a>タグ内の遷移を邪魔しないように、以下の場合はpreventDefaultを実行しない
      if ($(e.target).closest('a').length) {
        return; 
      }
      
      e.preventDefault();

      //クリックされた瞬間にモーダルを探す
      const addMenuModal = document.getElementById('menu-modal');
      if (!addMenuModal) return;

      //メニューボタンを押したらメニューが開く
      if ($(this).hasClass('menu-btn')) {
        addMenuModal.classList.add('is-open');
      }
      //×ボタン、または欄外を押したらメニューが閉じる
      else if ($(this).closest('#menu-close').length || e.target === addMenuModal) {
        addMenuModal.classList.remove('is-open');
      }
    });
  }

  //実行
  addTask()
});