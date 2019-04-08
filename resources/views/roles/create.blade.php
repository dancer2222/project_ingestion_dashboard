@extends('layouts.app')

@section("title", "Create a new role")

@section('content')

    @include('users.header')

    <div class="row">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('roles.store') }}" method="POST" class="form" id="create_role">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name" class=" form-control-label">
                                Role name
                            </label>
                            <input type="text" name="name" id="name" value="{{old('name')}}"
                                placeholder="Enter role name" 
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}">

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="permissions" class="form-control-label">
                                Permissions (hold `ctrl` to select multiple)
                            </label>

                            <select class="custom-select" multiple name="permissions[]">
                                @foreach ($permissions as $permission)

                                <option value="{{ $permission->id }}">{{ $permission->name }}</option>        

                                @endforeach
                            </select>

                            @if ($errors->has('permissions'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('permissions') }}</strong>
                                </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-outline-success">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
