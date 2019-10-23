@extends( 'auth.app' )

@section( 'title','Cài đặt lại mật khẩu' )

@section( 'content' )

    <div class="top">
        <a href="/">
            <img src="{{ asset('img/login_logo.png') }}" alt="MCQ">
        </a>
    </div>
    <div class="card">
        <div class="header">
            <p class="dev">{{ __('Cài đặt lại mật khẩu') }}</p>
        </div>
        <div class="body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group row">
                    <label for="email"
                           class="col-md-4 col-form-label text-md-right control-label sr-only">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" placeholder="{{ __('E-Mail Address') }}"
                               class="form-control @error('email') is-invalid @enderror" name="email"
                               value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

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
                           class="col-md-4 col-form-label text-md-right control-label sr-only">{{ __('Nhập lại mật khẩu') }}</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control"
                               placeholder="{{ __('Nhập lại mật khẩu') }}"
                               name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
