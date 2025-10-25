@extends('layouts.app')

@section('content')
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
<div id="auth">
    <div class="row h-100">
        <div class="col-lg-7 d-lg-block" style="padding-right: 0px">
            <div id="auth-right"></div>
        </div>
        <div class="col-lg-5 col-12 login-left">
            <div id="auth-left" class="mt-5">
                <!-- <div class="auth-logo">
                    <a href="index.html"><img src="assets/images/logo/logo.png" alt="Logo"></a>
                </div> -->
                <h2 class="text-center mt-5 text-white"><i class="bi bi-file-lock-fill"></i> Welcome back!</h2>
                <!-- <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p> -->

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <p class="text-white mb-2">Email</p>
                        <div class="form-group position-relative has-icon-left">
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Email">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <p class="text-white mb-2">Password</p>
                        <div class="form-group position-relative has-icon-left">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="*********">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                    </div>
                    @if (Route::has('password.request'))
                    <div class="form-group position-relative">
                        <a href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                    @endif
                    <!-- <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button> -->
                    <button type="submit" class="btn btn-light btn-block btn-lg shadow-lg mt-3">
                        {{ __('SIGN IN') }}
                    </button>
                    <div class="d-flex align-items-center my-4">
                        <hr class="flex-grow-1 text-light">
                        <span class="mx-3 text-white">OR</span>
                        <hr class="flex-grow-1 text-light">
                    </div>
                    <div class="form-group position-relative text-center">
                        <p class="text-white">Don't you have account?&nbsp;<a href="https://ticketing.rico.com.ph/itd/" class="text-white">Request Here</p>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
