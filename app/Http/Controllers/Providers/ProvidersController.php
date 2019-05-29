<?php

namespace App\Http\Controllers\Providers;

use App\Models\DataSourceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function view;

/**
 * Class ProvidersController
 * @package App\Http\Controllers\Providers
 */
class ProvidersController extends Controller
{
    /**
     * @param  DataSourceProvider  $dataSourceProvider
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(DataSourceProvider $dataSourceProvider, Request $request) {
        return view('misc.providers.index', ['providers' => DataSourceProvider::all()]);
    }
}
