<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- favicon -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Style --> 
    @php $v = '20260203'; @endphp
    <link rel="stylesheet" href="https://unpkg.com/destyle.css@3.0.2/destyle.min.css">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}?v={{ $v }}">
    <link rel="stylesheet" href="{{ asset('css/create.css') }}?v={{ $v }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}?v={{ $v }}">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}?v={{ $v }}">
    <link rel="stylesheet" href="{{ asset('css/task_modal.css') }}?v={{ $v }}">
    <link rel="stylesheet" href="{{ asset('css/master_tasks.css') }}?v={{ $v }}">

</head>
<body class="route-{{ str_replace('.','-', Route::currentRouteName()) }}">
    <div id="app">
        <header class="main-header">
            <div class="header-flex">
                <div class="menu-area">
                    <button id="menu-open" class="menu-btn"><img class="hamburger-icon" src="{{ asset('images/hamburgermenu_icon.png') }}" alt="ハンバーガーメニュー"></button>
    
                    <div id="menu-modal" class="menu-modal-overlay">
                        @include('layouts._menu_modal')
                    </div>
                </div>
                <a href="{{ route('tasks.index') }}"><div class="app-title">Mission Days</div></a>
                <div class="stamp-area">
                    <a class="stamp-btn" href="#"><img class="stamp-icon" src="{{ asset('images/stampcard.png') }}" alt="スタンプカードアイコン"></a>
                </div>
            </div>
        </header>

        <main class="main-content">
            @yield('content')
        </main>

        <footer class="main-footer">
            @if (isset($currentUser) && $currentUser->userStat)
            <div class="ticket-check-area">
                <div class="ticket-container">
                    <span class="reward-ticket">ごほうびチケット×</span>
                    <span class="ticket-count">{{ $currentUser->userStat->reward_tickets }}</span>
                </div>
                
                <div class="ticket-container">
                    <span class="edit-ticket">編集チケット×</span>
                    <span class="ticket-count">{{ $currentUser->userStat->edit_tickets }}</span>
                </div>
            </div>
            @endif
            <div class="copyright">
                <p>©daily_task_app Inc</p>
            </div>
        </footer>
    </div>
    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/swipe.js') }}"></script>
    <script src="{{ asset('js/task_modal.js') }}"></script>
    <script src="{{ asset('js/menu_modal.js') }}"></script>
    <script src="{{ asset('js/async.js') }}"></script>
</body>
</html>
