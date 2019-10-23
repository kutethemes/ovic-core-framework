@extends( 'auth.app' )

@section( 'title','Xác nhận đăng kí' )

@section( 'content' )

    <div class="header">
        <p class="dev">{{ __('Xác nhận email của bạn') }}</p>
    </div>
    <div class="card">
        <div class="body">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }},
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit"
                        class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>
                .
            </form>
        </div>
    </div>

@endsection
