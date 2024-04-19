<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <title>Game | @yield('title')</title>

    <style>
        nav {
            display: flex;
            justify-content: space-between;
            height: 70px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-home {
            width: 70px;
        }

        .titles {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 30px;
        }

        .menubar {
            display: flex;
            gap: 10px;
        }

        .logo {
            border: 2px solid black;
            height: 70px;
        }

        main {
            margin: 0 auto;
            width: 1200px;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <nav class="bg-slate-700 text-slate-200">
        <div class="titles">
            <a href="{{ route('dashboard') }}" class="btn-home"><img class="logo" src="/cica.png"></a>
            <h1>The Game</h1>
            @if (Auth::check())
                <div class="menubar">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Characters</a>
                </div>
            @endif
        </div>
        <div class="profile">
            @if (Auth::check())
                <span>Logged in as: <a href="{{ route('profile.edit') }}"
                        class="link">{{ Auth::user()->name }}</a></span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-primary" type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
            @endif
        </div>
    </nav>
    <main class="bg-slate-700 border border-slate-500 rounded p-5 mt-5 text-slate-200">
        @yield('content')
    </main>
    <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>
