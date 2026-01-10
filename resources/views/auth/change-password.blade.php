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
                @if (session()->has('status'))
                    <div class="alert alert-success" role="alert">
                        <strong>{{ session('status') }}</strong>
                    </div>
                @endif
                <h2 class="text-center">Reset Your Account Password</h2>
                <form class="login-form" method="POST" action="{{ route('auth.changeUserPassword') }}">
                    @csrf
                    <div class="form-group">
                        <label><b>New OTP</b></label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                            placeholder="New OTP">

                        @error('code')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label><b>New Password</b></label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label><b>Confirm Password</b></label>
                        <input type="password" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="Password Confirmation">

                        @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-login btn-block">Reset Password</button>
                    <div class="mt-2">
                        <p>No OTP Received? <a class="text-primary" href="{{ route('auth.sendOTP') }}"
                                style="color: #f37429;">Resend
                                OTP </a></p>
                    </div>
                </form>
            </div>

            <div class="banner-sec">
                <img src="{{ asset('assets/images/manbah_image.jpeg') }}" alt="Banner" class="img-fluid banner-img">
            </div>
        </div>
    </section>
</body>

</html>
