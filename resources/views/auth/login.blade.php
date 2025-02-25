<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Login || Ogroz </title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/core/bootstrap.css') }}">
    <style>
        body {
            font-family: Inter;
        }

        *,
        *::after,
        *::before {
            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }
    </style>
</head>

<body data-bs-theme='light'>
    <div class="bg-light-subtle px-2 ">
        <section class="d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">

            <form action="{{ route('login.submit') }}" method="POST" class="bg-body border rounded px-3 py-4"
                style="max-width: 350px; width: 96%;">
                @csrf
                @session('error')
                    <div class=" alert alert-danger text-sm text-center text-lowercase" role="alert">
                        {{ $value }}
                    </div>
                @endsession
                {{-- <h3 class="h5 text-center fw-bold">Login</h3> --}}
                <h1 class="h1 text-center fw-bold mb-3">Ogroz</h1>
                <p class="text-sm text-body mb-4 text-center text-muted">Enter your email & password to login</p>
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                        required value="{{ old('email') }}">
                    @error('email')
                        <p class="sm-text text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control " id="password" name="password" required
                        placeholder="Password" value="{{ old('password') }}">
                    @error('password')
                        <p class="sm-text text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary w-100 mt-3">Log In</button>
                </div>
            </form>
        </section>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let theme = localStorage.getItem('theme');
            document.body.setAttribute('data-bs-theme', theme == 'dark' ? 'dark' : 'light');
        });
    </script>
</body>

</html>
