@extends('admin.layouts.app')

@section('main-content')

<!-- Main content -->
<section class="content">

	<div class="box">
		<div class="box-body">
			<form method="POST" action="{{ route('admin.users.register') }}">
                @csrf

                <div class="form-group row">

                    <div class="col-xs-12 col-sm-3 col-lg-2">
                        <label for="first_name">{{ __('First Name') }}</label>
                    </div>

                    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-4">

                        <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" required autofocus>

                        @if ($errors->has('first_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>

                </div>
                <div class="form-group row">

                    <div class="col-xs-12 col-sm-3 col-lg-2">
                        <label for="last_name">{{ __('Last Name') }}</label>
                    </div>

                    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-4">

                        <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required autofocus>

                        @if ($errors->has('last_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-xs-12 col-sm-3 col-lg-2">
                        <label for="email">{{ __('E-Mail Address') }}</label>
                    </div>

                    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-4">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') || $errors->has('uuid') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-xs-12 col-sm-3 col-lg-2">
                        <label for="role">{{ __('Role') }}</label>
                    </div>

                    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-4">
                        <select id="role" class="form-control" name="role" >
                            <option value="2" {{ old('role',2) == 2 ? 'selected' : '' }}>Admin</option>
                            <option value="3" {{ old('role') == 3 ? 'selected' : '' }}>Manager</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </form>
		</div>
	</div>

</section>
<!-- /.content -->

@endsection