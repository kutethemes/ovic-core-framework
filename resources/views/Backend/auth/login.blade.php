@extends('auth.app')

@section('title','Đăng nhập')

@section('head')
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="top">
        <a href="/">
            <img src="{{ asset('img/login_logo.png') }}" alt="MCQ">
        </a>
    </div>
    <div class="card">
        <div class="header">
            <p class="dev">{{ __('Vui lòng đăng nhập để truy cập vào trang làm việc cá nhân của bạn') }}</p>
        </div>
        <div class="body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email"
                           class="control-label sr-only">{{ __('E-Mail') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" placeholder="{{ __('E-Mail') }}"
                               class="form-control @error('email') is-invalid @enderror" name="email"
                               value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password"
                           class="control-label sr-only">{{ __('Password') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" placeholder="{{ __('Password') }}"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" required autocomplete="current-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember"
                                   id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Ghi nhớ đăng nhập') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Đăng nhập') }}
                        </button>
                    </div>
                </div>

                @if (Route::has('password.request'))
                    <div class="bottom">
                        <span class="helper-text m-b-10">
                            <i class="fa fa-lock"></i>
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Bạn quên mật khẩu?') }}
                            </a>
                        </span>
                    </div>
                @endif

                @if ( Route::has('register') )
                    <div class="bottom">
                        <span>{{ __('Bạn chưa có tài khoản?') }} <a
                                    href="{{ route('register') }}">{{ __('Đăng ký miễn phí') }}</a></span>
                    </div>
                @endif
            </form>
        </div>
        <div class="dev">
            <small>{{ __('Trung tâm phần mềm © 2015 - 2019') }}</small>
        </div>
    </div>

@endsection
