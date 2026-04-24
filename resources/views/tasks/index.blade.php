@extends('layouts.app')

@section('content')
<div class="index-wrapper">
   <div class="date-area">
    @if (now()->format('Y/m/d') == $displayDate)
    <p class="today-p">今日</p>
    @else
    <p class="today-p">&emsp;</p>
    @endif
    <span class="target-day">{{ $displayDate }}</span>
   </div>

   <div class="task-area">

   <!-- ひとつ前の日付のタスク一覧 -->
   <div class="task-container" id="prev-task">
    </div>
    
     <a class="left-arrow arrow" href="{{ route('tasks.index', ['date' => $prevDate]) }}"></a>

       <div class="task-container
                  {{ (\Carbon\Carbon::parse($displayDate)->lt(today())) ? 'is-disabled' : '' }}"
            id="display-task">
         <ul class="task-list">
            @if ($tasks -> isNotEmpty())
            <form class="check-task" action="{{ route('tasks.change') }}" method="post">
              @csrf
              @foreach ($tasks as $task)
              <li class="task-card priority-{{ $task->priority }} 
                        {{ $task->is_completed ? 'is-completed' : '' }}
                        {{ (!$task->is_completed && $task->master_id) ? 'is-master' : '' }}">
                <label class="task-label">
                  <input type="checkbox" class="push-checkbox" name="check_id"
                      value="{{ $task->id }}"
                      {{ (\Carbon\Carbon::parse($displayDate)->lt(today())) ? 'disabled' : '' }}
                      {{ $task->is_completed ? 'checked' : '' }}>
                  <span class="task-title">{{ $task->title }}</span>
                </label>
              </li>
              @endforeach
            </form>
            @else
              <p class="no-task">タスクはありません</p>
            @endif
          </ul>
        </div>

    @if (\Carbon\Carbon::parse($displayDate)->lte(today()))
    <a class="right-arrow arrow" href="{{ route('tasks.index', ['date' => $nextDate]) }}"></a>
    @else
        {{-- 表示日が「明日」以降なら、右矢印は出さない（または空のdivで場所だけ確保） --}}
        <div class="arrow placeholder" style="visibility: hidden;"></div>
    @endif

    <!-- 次の日のタスク一覧 -->
     @if (\Carbon\Carbon::parse($displayDate)->lte(today()))
   <div class="task-container" id="next-task"></div>
    @else
        {{-- 表示日が「明日」以降なら、タスク一覧は出さない（または空のdivで場所だけ確保） --}}
    <div class="task-container placeholder" id="next-task" style="visibility: hidden;"></div>
    @endif

   </div>

   <div class="achievement-section">
    <p>達成率: {{ $rate }}%</p>
    <progress value="{{ $rate }}" max="100"></progress>
</div>

    <!-- タスクの登録、編集、変更ボタン -->
   <div class="btn-area">
    @if (now()->format('Y/m/d') == $displayDate)
      @if ($tasks -> whereNull('master_id') -> isEmpty())
      <div class="task-create-area">
        <button type="button" class="btn-primary task-create-btn">タスクを登録する</button>
      </div>
      @else
      <div class="use-edit-tickets">
        <button type="button" class="btn-primary edit-tickets-btn">タスクを変更する</button>
      </div>
      @endif
    @elseif (\Carbon\Carbon::parse($displayDate)->lt(today()))
    <div class="btn-primary placeholder" style="visibility: hidden;"></div>
    @elseif ($tasks -> whereNull('master_id') -> isNotEmpty())
    <div class="task-edit-area">
      <button type="button" class="btn-primary task-edit-btn">タスクを編集する</button>
    </div>
    @else
    <div class="task-create-area">
      <button type="button" class="btn-primary task-create-btn">タスクを登録する</button>
    </div>
    @endif
   </div>
   
   <div id="add-task-modal" class="modal-overlay">
    @include('tasks._create_modal', ['tasks' => $tasks, 'displayDate' => $displayDate])
   </div>
   
   <div id="edit-task-modal" class="modal-overlay">
    @include('tasks._edit_modal', ['tasks' => $tasks, 'displayDate' => $displayDate])
   </div>

   <div id="edit-tickets-modal" class="modal-overlay">
    @include('tasks._edit_tickets_modal', ['tasks' => $tasks, 'displayDate' => $displayDate, 'editTickets' => $editTickets])
   </div>

</div>
@endsection
