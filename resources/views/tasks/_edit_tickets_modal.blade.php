<div class="modal-content tickets-modal">
  @if ($editTickets <= 0)
  <div class="text-center">
    <div class="edit-tickets-area">
      <p>チケットを持っていません！</p>
    </div>
  </div>

    <div class="modal-btn">
    <button type="button" id="close-tickets-modal" class="modal-btn-primary modal-cancel-btn">いいえ</button>
  </div>
  @else
  <div class="text-center">
    <div class="edit-tickets-area">
      <p class="tickets-text">編集チケットを1枚消費します。よろしいですか？</p>
      <p class="tickets-text">{{ $editTickets }} → {{ $editTickets - 1 }}</p>
      <p class="tickets-annotation">※ここで「はい」を選んでも、編集が完了しない限りチケットは減りません。</p>
    </div>
  </div>

  <div class="modal-btn">
    <button type="button" id="close-tickets-modal" class="modal-btn-primary modal-cancel-btn">いいえ</button>
    <button type="button" class="modal-btn-primary confirm-yes-btn">はい</button>
  </div>
  @endif
</div>
