$(function() {
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  //チェックボックスへのチェック時
  function toggleStatus() {
    $(document).on('change', '.push-checkbox', function(e) {
      e.preventDefault();

      const $this = $(this);
      const $card = $this.closest('.task-card'); //親のliを探す
      const $form = $this.closest('form');
      const changeUrl = $form.attr('action');

      let $taskId = $this.val(); //クリックされたチェックボックスの情報を取得
      let $isCompleted = $this.prop('checked') ? 1 : 0; //チェックがあれば1、そうでなければ0

      $.ajax({
        url: changeUrl,
        type: 'POST',
        data: {
          check_id: $taskId,
          is_completed: $isCompleted,
          _token: $form.find('input[name="_token"]').val(),
        }
      })
      
      .done(function(data) {
        if (data.success) {

          if ($isCompleted === 1) {
              // 【完了したとき】
              $card.addClass('is-completed');
              // 日課タスクだったら、日課用の色クラスを一旦消す
              if (data.is_master) {
                  $card.removeClass('is-master');
              }
          } else {
              // 【完了を解除したとき】
              $card.removeClass('is-completed');
              // 日課タスクだったら、日課用の色クラスを復活させる
              if (data.is_master) {
                  $card.addClass('is-master');
              }
          }

          $('.achievement-section').html(`<p>達成率: ${data.new_rate}%</p>
                                          <progress value="{{ $rate }}" max="100"></progress>`);
        }
      })

      .fail(function(jqXHR, textStatus, errorThrown) {
          console.error('変更失敗:', textStatus, errorThrown);
          alert('ステータス変更に失敗しました。');
        });

    });
  }

  function deleteClick() {
    $(document).on('click', '#delete-btn', function(e) {
      e.preventDefault();

      const $this = $(this);
      const $form = $this.closest('form');
      const deleteUrl = $form.attr('action');
      const $targetElement = $this.closest('.master-item');

      $.ajax({
        url: deleteUrl,
        type: 'POST',
        data: $form.serialize(),
      })

      .done(function(data) {
        $targetElement.fadeOut(200, function() {
          $(this).remove();
        });

        $('#flash-message-container').html(
            '<div id="flash-message" class="alert-success delete-alert-success">'
            + data.message
            + '</div>'
          )

          setTimeout(function() {
            $('#flash-message').fadeOut(500, function() {
              $(this).remove(); // HTMLから完全に消去
            });
          }, 3000);
      })

      .fail(function(jqXHR, textStatus, errorThrown) {
          console.error('削除失敗:', textStatus, errorThrown);
          alert('削除に失敗しました。');
        });
    });
  }

  //実行
  deleteClick();
  toggleStatus();
});