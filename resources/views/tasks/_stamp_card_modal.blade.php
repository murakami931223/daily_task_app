<div class="stamp-modal-content">
  <div class="stamp-text-area">
    <p class="stamp-text">STAMP</p>
  </div>
  <div class="stamp-grid">
    @for ($i=0; $i < 10; $i++)
      <div class="stamp-item">
        @if ($i < $stampCount + $unreadStamps)
          <p class="stamp-mark {{ $i >= $stampCount ? 'stamp-animate' : '' }}">●</p>
        @endif
      </div>
    @endfor
  </div>
</div>

@if($unreadStamps > 0)
<script>
  document.addEventListener('DOMContentLoaded', function() {
    $('#stamp-card-modal').addClass('is-open');
    $('.stamp-animate').addClass('pop');

    setTimeout(function() {
      $.ajax({
          url: '{{ route("stamp.confirm") }}',
          method: 'POST',
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(data) {
              console.log('Ajax成功:', data);
              console.log('newCount:', {{ $stampCount + $unreadStamps }});
              console.log('stamp-icon要素:', $('.stamp-icon').length);

              if (data.gotReward) {
                  //チケット獲得演出
                  setTimeout(function() {
                    playRewardAnimation();
                  }, 300);
              } else {
                  const newCount = {{ $stampCount + $unreadStamps }};
                  $('.stamp-icon').attr('src', '{{ asset('images/') }}' + 'stamp_' + newCount + '.png');

                  setTimeout(function() {
                      $('#stamp-card-modal').removeClass('is-open');
                  }, 1800);
              }
          },
          error: function(xhr, status, error) {
              console.log('Ajax失敗:', error);
              // 失敗してもモーダルは閉じる（ユーザーを操作不能にしない）
              setTimeout(function() {
                  $('#stamp-card-modal').removeClass('is-open');
              }, 1800);
          }
      });
    }, 300);
  });

  //ごほうびチケット獲得のアニメーション演出
  function playRewardAnimation() {
      // スタンプカード全体を光らせながらフェードアウト
      $('.stamp-modal-content').addClass('card-fadeout');

      setTimeout(function() {
          // チケット画像を差し込む
          $('#stamp-card-modal').append(`
              <img id="reward-ticket" class="reward-ticket" src="{{ asset('images/reward_ticket.png') }}" alt="ごほうびチケット">
          `);

          $('#reward-ticket').addClass('ticket-appear');

          // アイコンを0枚にリセット
          $('.stamp-icon').attr('src', '{{ asset('images/stamp_0.png') }}');

          // モーダルを閉じてリセット
          setTimeout(function() {
              $('#stamp-card-modal').removeClass('is-open');

              setTimeout(function() {
                  $('#reward-ticket').remove();
                  $('.stamp-modal-content').removeClass('card-fadeout');

                  // スタンプマスを全てリセット
                  $('.stamp-item').each(function() {
                      $(this).find('.stamp-mark').remove();
                  });
              }, 500);

          }, 1800);

      }, 800);
  }
</script>
@endif
