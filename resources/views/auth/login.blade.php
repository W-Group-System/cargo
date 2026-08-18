<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cargo Monitoring System</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
        * {
            font-family: "Segoe UI", sans-serif;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            background: #f4f7fb;
        }

        .left-side {
            height: 100vh;
            background: url('{{ asset("images/cargo_login_bg.png") }}') center center no-repeat;
            background-size: 100% 100%;
            background-color: #ffffff; /* or your preferred background color */
        }

        .right-side {
            height: 100vh;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
        }

        .logo {
            width: 90px;
        }

        .system-title {
            font-weight: 700;
            color: #0B4F9C;
        }

        .system-subtitle {
            color: #777;
            margin-bottom: 35px;
        }

        .form-control {
            height: 52px;
            border-radius: 12px;
            padding-left: 45px;
            border: 1px solid #d6d6d6;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #0B4F9C;
        }

        .input-group-text {
            position: absolute;
            height: 52px;
            width: 45px;
            background: transparent;
            border: none;
            z-index: 10;
            color: #888;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .btn-login {
            height: 52px;
            border-radius: 12px;
            font-weight: 600;
            background: #0B4F9C;
            border: none;
            transition: .3s;
        }

        .btn-login:hover {
            background: #083d79;
        }

        .forgot-password {
            text-decoration: none;
            color: #0B4F9C;
            font-size: .9rem;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #999;
            font-size: .85rem;
        }

        @media(max-width:991px) {

            .left-side {
                display: none;
            }

            .right-side {
                width: 100%;
            }

        }
    </style>

</head>

<body>

    <div class="container-fluid p-0">

        <div class="row g-0">

            <!-- LEFT IMAGE -->

            <div class="col-lg-6 left-side"></div>

            <!-- RIGHT LOGIN -->

            <div class="col-lg-6 right-side">

                <div class="login-box">

                    <div class="text-center mb-4">

                        <img src="{{ asset('images/logo-only.png') }}" class="logo mb-3">

                        <h2 class="system-title">
                            Cargo Monitoring
                        </h2>

                        <div class="system-subtitle">
                            Sign in to continue
                        </div>

                    </div>

                    <form method="POST" action="{{ route('login') }}">
                    @csrf
                        <div class="form-group">

                            <span class="input-group-text">
                                <i class="fa fa-user"></i>
                            </span>

                            <input type="text"
                                class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}"
                                placeholder="Email" required>
                            
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif

                        </div>

                        <div class="form-group">

                            <span class="input-group-text">
                                <i class="fa fa-lock"></i>
                            </span>

                            <input type="password"
                                class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                placeholder="Password" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif

                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="form-check">

                                <input class="form-check-input"
                                    type="checkbox"
                                    id="remember">

                                <label class="form-check-label" for="remember">
                                    Remember Me
                                </label>

                            </div>

                            <a href="#" class="forgot-password">
                                Forgot Password?
                            </a>

                        </div>

                        <button class="btn btn-primary btn-login w-100" type="submit">

                            <i class="fa-solid fa-right-to-bracket me-2"></i>

                            Login

                        </button>

                    </form>

                    <div class="footer">
                       
                        © 2026 W Group Inc.<br>

                        Cargo Monitoring System <br>

                        Version 1.1.0 

                    </div>

                </div>

            </div>

        </div>

    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('error'))
            Swal.fire({
                title: 'Unauthorized!',
                text: @json(session('error')),
                icon: 'error'
            });
        @endif
    </script>

</body>

</html>