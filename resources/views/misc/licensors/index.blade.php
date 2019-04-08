@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6 mx-auto card">
            <div class="card-body">
                <search-form></search-form>
                <licensors-list></licensors-list>
            </div>
        </div>
    </div>
@endsection
