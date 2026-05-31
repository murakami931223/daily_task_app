$(function () {
  //セレクトボタンの色変え
  $(document).on('change', '.priority-select', function () {
    const $select = $(this);
    const val = $select.val();

    $select.removeClass('p-1 p-2 p-3').addClass('p-' + val);
  })

  //モーダル表示時に初期色を反映
  function initPriorityColor() {
    $('.priority-select').each(function () {
      const val = $(this).val();
      $(this).addClass('p-' + val);
    });
  }

  function openModal() {
    $(document).on('click', '[data-open]', function (e) {
      e.preventDefault();

      const modalId = $(this).data('open');
      $('#' + modalId).addClass('is-open');
    });
  }

  function closeModal() {
    $(document).on('click', '[data-close]', function (e) {

      // 背景クリック以外は無視
      if ($(this).hasClass('modal-overlay') && e.target !== this) {
        return;
      }

      e.preventDefault();

      const modalId = $(this).data('close');
      //閉じる処理
      $('#' + modalId).removeClass('is-open');

      //リセット処理（登録モーダルの時のみ実行）
      if (modalId === 'add-task-modal') {
        setTimeout(function () {
          const $container = $('#add-input-container');
          $container.find('.input-row').not(':first').remove();
          const $firstRow = $container.find('.input-row').first();
          $firstRow.find('input[type="text"]').val('');
          $firstRow.find('.priority-select').val('2').removeClass('p-1 p-3').addClass('p-2');
        }, 300);
      }
    });
  }

  function confirmTicketModal() {
    $(document).on('click', '.confirm-yes-btn', function (e) {
      e.preventDefault();

      $('#edit-tickets-modal').removeClass('is-open'); // まずチケット確認画面を閉じる
      $('#edit-task-modal').addClass('is-open'); // 次に編集モーダルを開く

    });
  }

  //入力欄を増やす
  function addInput() {
    $(document).on('click', '#add-input, #edit-input', function (e) {
      e.preventDefault();

      //ボタンのdata-target属性を取得
      const targetSelector = $(this).data('target');
      const $container = $(targetSelector);

      //最初の.input-rowをコピーする
      const $FirstRow = $container.find('.input-row').first();
      const $newRow = $FirstRow.clone();

      //コピーした行の中身を削除する
      $newRow.find('input[type="text"]').val('');
      //編集画面の場合、hiddenのIDを空にする
      $newRow.find('input[type="hidden"]').val('');
      //優先度は初期値（2）にしておく
      $newRow.find('.priority-select').val('2').removeClass('p-1 p-3').addClass('p-2');

      //削除ボタンを付ける
      if ($newRow.find('.remove-btn').length === 0) {
        $newRow.append(`<button type="button" class="remove-btn">×</button>`);
      }

      $container.append($newRow);
    });
  }

  //削除ボタンの動作
  $(document).on('click', '.remove-btn', function () {
    $(this).closest('.input-row').remove();
  });


  //実行
  initPriorityColor()
  addInput()
  openModal()
  closeModal()
  confirmTicketModal()
});