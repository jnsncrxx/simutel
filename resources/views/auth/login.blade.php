<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚜️</text></svg>">
    <title>Login</title>
    <link rel="stylesheet" href="home/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+SC:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar bg-dark p-0 m-0">
        <div class="container-fluid">
            <a class="navbar-brand m-auto" href="/">PUPSJhotel</a>
        </div>
    </nav>
    <main class="container">
        <div class="container my-4 mx-auto">
            <form method="POST" action="{{ route('login') }}" class="card row gy-2">
                @csrf
                <h2>Login</h2>
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p class="m-0">{{ $error }}</p>
                    @endforeach
                @endif
                @if (session('status'))
                    <p class="m-0 text-info">
                        {{ session('status') }}
                    </p>
                @endif
                <div class="col-12 mt-3">
                    <label for="login">{{ __('Email or Username') }}</label>
                    <input id="login" class="col-12" type="text" name="login" :value="old('login')" required autofocus />
                </div>

                <div class="col-12">
                    <label for="password">{{ __('Password') }}</label>
                    <input id="password" class="col-12" type="password" name="password" required autocomplete="current-password" />
                </div>

                <div class="mt-2">
                    <label for="show_password">
                        <input type="checkbox" id="show_password" onclick="(document.getElementById('password').type === 'password')?document.getElementById('password').type = 'text':document.getElementById('password').type = 'password'">
                        <span>Show Password</span>
                    </label>
                </div>

                <div class="row mt-1 mx-auto">
                    <div class="col-12 mt-3 p-0 text-center">
                        <button type="submit" class="btn btn-secondary col-12 col-sm-6">{{ __('Login') }}</button>
                        @if (Route::has('password.request'))
                            <a class="col-12 col-sm-6 ps-2" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>
                    <div class="col-12 mt-4 pt-3 text-center">
                        <p>Don't have an account?
                            <a href="/register">Register</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

