@extends('admin.layouts.app')

@section('main-content')

@php
    $user = Auth::user();
    $profile = App\Model\Admin\Admin::find( request()->route()->parameters['user'] );
@endphp

<!-- Main content -->
<section class="content">

	<div class="box">
		<div class="box-body">
			<form method="POST" action="{{ route('admin.users.update', $profile->id) }}">
                @csrf

                {{ method_field('PUT') }}

                <div class="form-group row">

                    <div class="col-xs-12 col-sm-3 col-lg-2">
                        <label for="first_name">{{ __('First Name') }}</label>
                    </div>

                    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-4">

                        <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name', $profile->first_name) }}" required>

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

                        <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name', $profile->last_name) }}" required>

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
                        <input id="email" type="email" class="form-control{{ $errors->has('email') || $errors->has('uuid') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', $profile->email) }}" required>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-xs-12 col-sm-3 col-lg-2">
                        <label for="password">{{ __('Password') }}</label>
                    </div>

                    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-4">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-xs-12 col-sm-3 col-lg-2">
                        <label for="role">{{ __('Role') }}</label>
                    </div>

                    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-4">
                        @if ($profile->id == 1)
                            <p>{{ admin_user($profile->role) }}</p>
                            <input type="hidden" name="role" id="role" value="{{ $profile->role }}">
                        @elseif ($user->role == 1 && intval($user->id) !== intval($profile->id))
                        <select id="role" class="form-control" name="role" >
                                <option value="1" {{ old('role',$profile->role) == 1 ? 'selected' : '' }}>Super Admin</option>
                                <option value="2" {{ old('role',$profile->role) == 2 ? 'selected' : '' }}>Admin</option>
                                <option value="3" {{ old('role',$profile->role) == 3 ? 'selected' : '' }}>Manager</option>
                        </select>
                        @else
                            <p>{{ admin_user($profile->role) }}</p>
                            <input type="hidden" name="role" id="role" value="{{ $profile->role }}">
                        @endif
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