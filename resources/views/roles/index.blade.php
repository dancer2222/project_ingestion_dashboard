@extends('layouts.app')

@section("title", "Roles / Permissions")

@section('content')

    @include('users.header')

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <h3 class="card-header">Roles</h3>
                <div class="card-body">
                    <table class="table table-stripped">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($roles as $role)              
                
                            <tr>
                                <th scope="row">{{ $role->id }}</th>
                                <td>{{ $role->name }}</td>
                                <td class="text-right">
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-info {{ $role->name == 'admin' ? 'disabled' : '' }}" title="Edit" {{ $role->name == 'admin' ? 'aria-disabled="true"' : '' }}>
                                            <i class="fas fa-user-edit"></i>
                                    </a>

                                    <button class="btn btn-sm btn-outline-danger" title="Remove" {{ $role->name == 'admin' ? 'disabled' : '' }}
                                        onclick="document.getElementById('form_remove_role_{{$role->id}}').submit()">
                                            <i class="fas fa-user-times"></i>
                                    </button>

                                    <form action="{{ route('roles.destroy', $role->id) }}" id="form_remove_role_{{$role->id}}" method="post">
                                            {{ csrf_field() }}
                                    </form>
                                </td>
                            </tr>
                
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
                <div class="card">
                    <h3 class="card-header">Permissions</h3>
                    <div class="card-body">
                        <table class="table table-stripped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($permissions as $permission)              
                    
                                <tr>
                                    <th scope="row">{{ $permission->id }}</th>
                                    <td>{{ $permission->name }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                                <i class="fas fa-user-edit"></i>
                                        </a>
    
                                        <button class="btn btn-sm btn-outline-danger" title="Remove"
                                            onclick="document.getElementById('form_remove_perm_{{$permission->id}}').submit()">
                                                <i class="fas fa-user-times"></i>
                                        </button>
    
                                        <form action="{{ route('permissions.destroy', $permission->id) }}" id="form_remove_perm_{{$permission->id}}" method="post">
                                                {{ csrf_field() }}
                                        </form>
                                    </td>
                                </tr>
                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>

@endsection
