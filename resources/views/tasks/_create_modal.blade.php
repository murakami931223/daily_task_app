<div class="modal-content">
  <form id="task-form" action="{{ route('tasks.store') }}" method="POST">
    @csrf
    <div class="modal-input-area">
      <input type="hidden" name="task_date" value="{{ $displayDate }}">
      <div class="priority-header">
        <p>優先度</p>
      </div>
      <div id="add-input-container">
        @error('titles.*')
        <div class="error-message">{{ $message }}</div>
        @enderror
        <div class="input-row">
            <select class="priority-select" name="priorities[]">
              @foreach (App\Models\Task::PRIORITIES as $val => $label)
              <option value="{{ $val }}" {{ $val ==2 ? 'selected' : '' }}>
                {{ $label }}
              </option>
              @endforeach
            </select>
            <input type="text" name="titles[]" placeholder="タスクを入力" required>
        </div>
      </div>
      <button id="add-input" data-target="#add-input-container" class="icon icon-plus" type="button"></button>
    </div>

    <div class="modal-btn">
      <button type="button" id="close-add-modal" class="modal-btn-primary modal-cancel-btn">キャンセル</button>
      <button type="submit" class="modal-btn-primary modal-create-btn">登録</button>
    </div>
  </form>
</div>
