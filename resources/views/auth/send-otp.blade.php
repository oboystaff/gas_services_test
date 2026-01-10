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
                <h2 class="text-center" style="margin-bottom: 100px">Reset You Account Password</h2>
                @if (session()->has('status'))
                    <div class="alert alert-success" role="alert">
                        <strong>{{ session('status') }}</strong>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ session('error') }}</strong>
                    </div>
                @endif
                <form class="login-form" method="POST" action="{{ route('auth.sendUserOTP') }}">
                    @csrf
                    <div class="form-group" style="margin-bottom: 20px">
                        <label><b>Account Phone Number</b></label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            placeholder="Account Phone Number">

                        @error('phone')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-login btn-block">Send OTP</button>
                </form>
            </div>

            <div class="banner-sec">
                <img src="{{ asset('assets/images/manbah_image.jpeg') }}" alt="Banner" class="img-fluid banner-img">
            </div>
        </div>
    </section>
</body>

</html>
