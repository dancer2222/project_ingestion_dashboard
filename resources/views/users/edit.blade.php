@extends('layouts.app')

@section("title", "Edit user")

@section('content')

    @include('users.header')

    <div class="row">
        <div class="col-12 col-sm-8 col-md-7 col-lg-5 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST" class="form" id="create_user">
                        {{ csrf_field() }}

                        @foreach ($user->getFillable() as $field)
                        @if($field !== 'password')
                            <div class="form-group">
                                <label for="{{$field}}" class=" form-control-label">
                                    {{ Str::title($field) }}
                                </label>

                                
                                 <input type="text" name="{{$field}}" id="{{$field}}" value="{{$user[$field]}}"
                                    placeholder="Enter {{$field}}" 
                                    class="form-control{{ $errors->has($field) ? ' is-invalid' : '' }}">
                                

                                @if ($errors->has($field))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first($field) }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endif
                        @endforeach

                        <div class="form-group">
                            <label for="roles" class="form-control-label">
                                Roles (hold `ctrl` to select multiple)
                            </label>

                            <select class="custom-select" multiple name="roles[]">
                                @foreach ($roles as $role)

                                <option value="{{ $role->id }}" {{ $user->hasRole($role->id) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>        

                                @endforeach
                            </select>

                            @if ($errors->has('roles'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('roles') }}</strong>
                                </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-outline-success">Update</button>
                    </form>
                    
                    <hr>

                    <p>Change password</p>
                    <form action="{{ route('users.password', $user->id) }}" method="POST" class="form" id="create_user">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="password" class=" form-control-label">
                                Password
                            </label>

                                <input type="text" name="password" id="password" value=""
                                placeholder="Enter password" 
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                            

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-outline-success">Change</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
