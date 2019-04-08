@extends('layouts.app')

@section("title", "Users")

@section('content')

    @include('users.header')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                        <table class="table table-stripped">
                                <thead class="thead-dark">
                                    <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Name</th>
                                      <th scope="col">Username</th>
                                      <th scope="col">Email</th>
                                      <th scope="col">Created at</th>
                                      <th scope="col"></th>
                                  </tr>
                              </thead>
                              <tbody>
                                @foreach ($users as $user)              
                    
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                                <i class="fas fa-user-edit"></i>
                                        </a>

                                        <button class="btn btn-sm btn-outline-danger" title="Remove"
                                            onclick="document.getElementById('form_remove_user_{{$user->id}}').submit()">
                                                <i class="fas fa-user-times"></i>
                                        </button>

                                        <form action="{{ route('users.destroy', $user->id) }}" id="form_remove_user_{{$user->id}}" method="post">
                                                {{ csrf_field() }}
                                        </form>
                                    </td>
                                </tr>
                    
                                @endforeach(users)
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>

@endsection
