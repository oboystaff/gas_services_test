<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gas Service System</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/login-style.css') }}" rel="stylesheet">

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>
    <section class="login-block">
        <div class="login-container">
            <div class="login-sec">
                <h2 class="text-center">Login Now</h2>
                <form class="login-form" method="POST" action="{{ route('auth.login') }}">
                    @csrf
                    <div class="form-group">
                        <label><b>Phone Number</b></label>
                        <input type="text" name="username"
                            class="form-control @error('username') is-invalid @enderror" placeholder="Phone Number">
                        @error('username')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label><b>Password</b></label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input">
                            <small>Remember Me</small>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login btn-block">Submit</button>
                </form>
            </div>

            <div class="banner-sec">
                <img src="{{ asset('assets/images/manbah_image.jpeg') }}" alt="Banner" class="img-fluid banner-img">
            </div>
        </div>
    </section>
</body>

</html>
