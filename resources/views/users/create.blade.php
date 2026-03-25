@extends('layouts.app')

@section('content')
<div class="create-wrapper">
    <div class="wellcome-message">
        <p>Mission Daysにようこそ！</p>
        <p>まずはユーザー登録を行ってください。</p>
    </div>
    <div class="create-box">
        <p class="create-heading">新規登録</p>
        <form method="POST" action="{{ route('store') }}"  enctype="multipart/form-data">
             @csrf
             <div class="create-container">
                 @error('name')
                     <div class="error-message" style="color: red;">{{ $message }}</div>
                 @enderror
                 <div class="flex-input-area">
                     <label for="name" class="create-name">ユーザーネーム</label>
                     <div class="name-area">
                         <input id="name" class="create-input" name="name" value="{{ old('name') }}">
                     </div>
                 </div>
             </div>
     
             <div class="create-btn-container">
                 <input class="create-btn" type="submit" value = "登録" >
             </div>
         </form>
    </div>
</div>
@endsection
