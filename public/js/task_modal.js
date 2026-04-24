$(function () {
  //セレクトボタンの色変え
    $(document).on('change', '.priority-select', function() {
      const $select = $(this);
      const val = $select.val();
  
      $select.removeClass('p-1 p-2 p-3').addClass('p-' + val);
    })

    //モーダル表示時に初期色を反映
    function initPriorityColor() {
      $('.priority-select').each(function() {
        const val = $(this).val();
        $(this).addClass('p-' + val);
      });
    }

  function manageTaskModal() {
    //登録ボタンや編集ボタン、閉じるボタン、モーダル背景を一括監視
    $(document).on('click', 
      '.task-create-btn, .task-edit-btn, .edit-tickets-btn, #close-add-modal, #close-edit-modal, #close-tickets-modal, #add-task-modal, #edit-task-modal, #edit-tickets-modal', function(e) {
     
      //送信ボタンなら何もしない
      if ($(e.target).closest('button[type="submit"]').length) return;

      e.preventDefault();

      //ターゲットとなるモーダルを特定する
      //ボタンのクラス名やIDから、操作すべきモーダルを自動判別
      let targetModalId = '';
      if ($(this).hasClass('task-create-btn') || $(this).is('#add-task-modal, #close-add-modal')) {
        targetModalId = 'add-task-modal';
      }else if ($(this).hasClass('task-edit-btn') || $(this).is('#edit-task-modal, #close-edit-modal')) {
        targetModalId = 'edit-task-modal';
      }else if ($(this).hasClass('edit-tickets-btn') || $(this).is('#edit-tickets-modal, #close-tickets-modal')) {
        targetModalId = 'edit-tickets-modal';
      }

      const $modal = $('#' + targetModalId);
      if (!$modal.length) return;

      //表示・非表示の切り替え
      if ($(this).hasClass('task-create-btn') || $(this).hasClass('task-edit-btn') || $(this).hasClass('edit-tickets-btn')) {
        //開く処理
        $modal.addClass('is-open');

        //「タスクを変更する」ボタンの時、「はい」を押したらis-openクラスを削除
        if ($(this).hasClass('edit-tickets-btn')) {
          if($(this).hasClass('task-edit-btn')) {
            $modal.removeClass('is-open');
          }
        }
      } else if ($(this).is('#close-add-modal, #close-edit-modal, #close-tickets-modal') || e.target.id === targetModalId) {
        //閉じる処理
        $modal.removeClass('is-open');

        //リセット処理（登録モーダルかつ閉じた時のみ実行）
        if (targetModalId === 'add-task-modal') {
          setTimeout(function() {
            const $container = $('#add-input-container');
            $container.find('.input-row').not(':first').remove();
            const $FirstRow = $container.find('.input-row').first();
            $FirstRow.find('input[type="text"]').val('');
            $FirstRow.find('.priority-select').val('2').removeClass('p-1 p-3').addClass('p-2');
          }, 300);
        }
      }
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
  manageTaskModal()
});