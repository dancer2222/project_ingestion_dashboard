@extends('layouts.app')

@section("title", "Edit permission")

@section('content')

    @include('users.header')

    <div class="row">
        <div class="col-12 col-sm-8 col-md-7 col-lg-5 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('permissions.update', $permission->id) }}" method="POST" class="form" id="create_role">
                        {{ csrf_field() }}

                        
                        <div class="form-group">
                            <label for="name" class=" form-control-label">
                                Name
                            </label>

                            
                            <input type="text" name="name" id="name" value="{{ $permission->name }}"
                                placeholder="Enter role name" 
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}">
                            

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-outline-success">Update</button>
                    </form>
                
                </div>
            </div>
        </div>
    </div>

@endsection
