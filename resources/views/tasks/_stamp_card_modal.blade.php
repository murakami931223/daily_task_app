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
              
              if (data.success) {
                  const newCount = {{ $stampCount + $unreadStamps }};
                  $('.stamp-icon').attr('src', '/images/stamp_' + newCount + '.png');

                  setTimeout(function() {
                      $('#stamp-card-modal').removeClass('is-open');
                  }, 2300);
              }
          },
          error: function(xhr, status, error) {
              console.log('Ajax失敗:', error);
              // 失敗してもモーダルは閉じる（ユーザーを操作不能にしない）
              setTimeout(function() {
                  $('#stamp-card-modal').removeClass('is-open');
              }, 2300);
          }
      });
    }, 800);
  });
</script>
@endif
