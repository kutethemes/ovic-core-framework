@extends('auth.app')

@section('title','Đăng kí')

@section('content')

    <div class="top">
        <a href="/">
            <img src="{{ asset('img/login_logo.png') }}" alt="MCQ">
        </a>
    </div>
    <div class="card">
        <div class="header">
            <p class="dev">{{ __('Đăng ký tài khoản') }}</p>
        </div>
        <div class="body">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right control-label sr-only">{{ __('Name') }}</label>

                    <div class="col-md-6">
                        <input id="name" type="text" placeholder="{{ __('Name') }}"
                               class="form-control @error('name') is-invalid @enderror" name="name"
                               value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email"
                           class="col-md-4 col-form-label text-md-right control-label sr-only">{{ __('E-Mail') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" placeholder="{{ __('E-Mail') }}"
                               class="form-control @error('email') is-invalid @enderror" name="email"
                               value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password"
                           class="col-md-4 col-form-label text-md-right control-label sr-only">{{ __('Mật khẩu') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" placeholder="{{ __('Mật khẩu') }}"
                               class="form-control @error('password') is-invalid @enderror" name="password"
                               required autocomplete="new-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm"
                           class="col-md-4 col-form-label text-md-right control-label sr-only">{{ __('Xác nhận mật khẩu') }}</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" placeholder="{{ __('Xác nhận mật khẩu') }}"
                               name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Đăng kí') }}
                        </button>
                    </div>
                </div>

                <div class="bottom">
                        <span>{{ __('Bạn đã có tài khoản?') }} <a
                                    href="{{ route('login') }}">{{ __('Đăng nhập tại đây') }}</a></span>
                </div>
            </form>
        </div>
        <div class="dev">
            <small>{{ __('Trung tâm phần mềm © 2015 - 2019') }}</small>
        </div>
    </div>

@endsection
