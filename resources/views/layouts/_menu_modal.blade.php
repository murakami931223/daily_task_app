<div class="menu-modal-content">
  <div class="menu-header">
    <button id="menu-close" class="menu-close-btn">
      <img src="{{ asset('images/close_btn.png') }}" alt="閉じるボタン">
    </button>
  </div>
  <div class="menu-list-container">
    <ul class="menu-list">
      <li class="menu-item">
        <a class="menu-item-flex" href="{{ route('tasks.index') }}">
          <img src="{{ asset('images/home.png') }}">
          <span>ホーム</span>
        </a> 
      </li>
      <li class="menu-item">
        <a class="menu-item-flex" href="{{ route('tasks.show_master') }}">
          <img src="{{ asset('images/input_checklist.png') }}">
          <span>日課タスク</span>
        </a> 
      </li>
      <li class="menu-item">
        <a class="menu-item-flex" href="#">
          <img src="{{ asset('images/gear.png') }}">
          <span>設定</span>
        </a> 
      </li>
      <li class="menu-item">
        <a class="menu-item-flex" href="#">
          <img src="{{ asset('images/help.png') }}">
          <span>使い方ガイド</span>
        </a> 
      </li>
    </ul>
  </div>
</div>