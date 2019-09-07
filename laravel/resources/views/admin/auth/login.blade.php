
@include('auth.layouts.header')

<div class="login-box">
    <div class="login-logo">
        <img src="{{ asset('assets/images/apple-icon-72x72.png') }}" alt="" style="width:72px">
    </div>
    <div class="login-box-body">
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            @honeypot

            <div class="form-group has-feedback">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="Email">

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">

                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group row">
                <div class="col-sm-8">
                    <div class="checkbox icheck">
                        <label for="remember">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>

                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Login') }}
                    </button>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-xs-12">
                    @lcaptcha
                </div>
            </div>
        </form>
    </div>
</div>


@include('auth.layouts.footer')
