
@include('auth.layouts.header')



<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>Dash</b>Board</a>
    </div>
    {{-- <div class="login-box-body"> --}}
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#signup" data-toggle="tab">Sign Up</a>
                </li>
                <li>
                    <a href="#login" data-toggle="tab">Login</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="signup">
                    <form method="POST" action="{{ route( 'user.register') }}">
                        @csrf

                        <input type="hidden" name="campaign" value="{{ $post->id }}">

                        @if (count($errors) > 0)
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        @endif

                        <div class="form-group has-feedback">

                            <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" required placeholder="First Name">

                        </div>
                        <div class="form-group has-feedback">

                            <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required placeholder="Last Name">

                        </div>
                        <div class="form-group has-feedback">

                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Email">

                        </div>
                        <div class="form-group has-feedback">

                            <input id="password" type="password" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="password" value="{{ old('email') }}" required placeholder="Password">

                        </div>

                        <div class="form-group has-feedback">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Register') }}
                            </button>
                        </div>

                    </form>
                </div>
                <div class="tab-pane" id="login">
                    <form method="POST" action="{{ route( 'user.login') }}">
                        @csrf

                        <input type="hidden" name="campaign" value="{{ $post->id }}">

                        @if ($errors->has('status'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('status') }}</strong>
                            </span>
                        @endif

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

                        <div class="row">
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
                    </form>
                </div>
            </div>
        </div>
    {{-- </div> --}}
</div>


@include('auth.layouts.footer')
