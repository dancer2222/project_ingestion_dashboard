<?php

namespace App\Http\Controllers\Providers;

use App\Models\DataSourceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function view;

class ProvidersController extends Controller
{
    public function index(DataSourceProvider $dataSourceProvider, Request $request) {
        return view('misc.providers.index', ['providers' => DataSourceProvider::all()]);
    }
}
