<?php

use App\Models\Company;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/teste', function (Illuminate\Http\Request $request) {
    {
         $filter='';
        DB::enableQueryLog();
       return  response(Company::where([
            ['company_type', 'like','%'.$request->query('company_type').'%']
        ])
            ->orWhere('name', 'like', "%$filter%")
           ->orWhere('telephone', 'like',"%$filter%")
            ->orWhere('address', 'like',"%$filter%")
            ->orWhere('address1', 'like',"%$filter%")
            ->orWhere('address2', 'like',"%$filter%")

            ->count());
        dd(DB::getQuerylog());
        DB::enableQueryLog();
        Vehicle::where('company_id', 3)
            ->where('name', 'like', '%'.$request->query('search').'%')
            ->get();
        dd(DB::getQuerylog());
        return response()->json();
    dd(App::getLocale());
    return view('welcome');
}});
