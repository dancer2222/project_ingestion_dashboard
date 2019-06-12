@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
                       @foreach($message as $key => $value)
                           {{ $key }} - {{ $value }}
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection