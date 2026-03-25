@extends('layouts.app')

@section('content')
<div class="master-tasks-container">
  <div class="master-content">
    <form id="task-form" action="{{ route('tasks.master_task.store') }}" method="POST">
      @csrf
      <div class="master-input-area">
        <div id="input-container">
          @error('titles.*')
          <div class="error-message">{{ $message }}</div>
          @enderror
          <div class="input-row master-input-row">
            <input type="text" name="titles[]" placeholder="タスクを入力" required>
          </div>
        </div>
        <button id="add-input" class="icon icon-plus" type="button"></button>
      </div>
      
      <div class="master-btn">
        <button type="submit" class="master-btn-primary master-create-btn">登録</button>
      </div>
    </form>
  </div>
  
  <div class="created-master-list-container">
    <div class="master-list-header">登録済み日課タスク</div>
    <ul class="master-list-content">
      @if ($tasks -> isNotEmpty())
      @foreach ($tasks as $task)
      <li class="master-item">
        <p class="master-item-title">{{ $task -> title }}</p>
          <form action="{{ route('tasks.master_task.delete', ['id' => $task -> id ]) }}" method="POST">
            @csrf
            @method('delete')
            <input data-task_id="{{ $task->id }}" id="delete-btn" class="master-delete-btn" type="submit" value = "×" >
          </form>
        </li>
        @endforeach
      @else
        <p class="master-no-task">登録済みタスクはありません</p>
      @endif
    </ul>
    </div>
  </div>
  <div id="flash-message-container">
    @if (session('ajax_success'))
    <div id="flash-message" class="alert-success delete-alert-success">
        {{ session('ajax_success') }}
    </div>
    @endif
  </div>
  @endsection
  