@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        <providers-list :providers="{{ $providers }}"></providers-list>
                        <a href="{{route('providers.index')}}">asdasdas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
