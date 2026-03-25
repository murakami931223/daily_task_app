<div class="modal-content">
  <form id="task-form" action="{{ route('tasks.update') }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-input-area">
      <input type="hidden" name="task_date" value="{{ $displayDate }}">
      <div class="priority-header">
        <p>優先度</p>
      </div>
      <div id="edit-input-container">
        @error('titles.*')
        <div class="error-message">{{ $message }}</div>
        @enderror

        @foreach ($tasks as $task)
          <div class="input-row">
            <input type="hidden" name="task_ids[]" value="{{ $task->id }}">
            
            <select class="priority-select" name="priorities[]">
              @foreach (App\Models\Task::PRIORITIES as $val => $label)
              <option value="{{ $val }}" {{ $task->priority == $val ? 'selected' : '' }}>
                {{ $label }}
              </option>
              @endforeach
            </select>
            <input type="text" name="titles[]" value="{{ $task->title }}" required>
            <button type="button" class="remove-btn">×</button>
          </div>
        @endforeach

      </div>
      <button id="edit-input" data-target="#edit-input-container" class="icon icon-plus" type="button"></button>
    </div>

    <div class="modal-btn">
      <button type="button" id="close-edit-modal" class="modal-btn-primary modal-cancel-btn">キャンセル</button>
      <button type="submit" class="modal-btn-primary modal-edit-btn">更新</button>
    </div>
  </form>
</div>
