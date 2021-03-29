<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Crimes;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('crimes', function() {
    //return crimes::paginate(15);
    return crimes::where([
        ['fecha_hechos', '!=', 'NA'],
        ['fecha_hechos', '!=', 'fecha_hechos']
        ])
        ->orderBy('fecha_hechos', 'desc')
        ->paginate(15);
});

Route::get('crimes/{lat}/{long}', function(Request $request, $lat, $long) { 
    return crimes::where([
        ['fecha_hechos', '!=', 'NA'],
        ['fecha_hechos', '!=', 'fecha_hechos'],
        ['latitud', 'like', "{$lat}%"],
        ['longitud', 'like', "{$long}%"]
        ])
        ->orderBy('fecha_hechos', 'desc')
        ->paginate(15);
});

Route::get('crimes/delito/{delito}', function(Request $request, $delito) {
    //return crimes::paginate(15);
    return crimes::where([
        ['fecha_hechos', '!=', 'NA'],
        ['fecha_hechos', '!=', 'fecha_hechos'],
        ['delito', $delito]
        ])
        ->orderBy('fecha_hechos', 'desc')
        ->paginate(15);
});

// SELECT COUNT(*) as count, delito FROM crimes WHERE fecha_hechos LIKE '2021-02%' GROUP BY delito ORDER BY count DESC;
Route::get('crimes/resume/', function() {
    //return crimes::paginate(15);
    return crimes::selectRaw('count(*) as crime_count, delito')
        ->where('fecha_hechos', 'like', "2021-02%")
        ->groupBy('delito')
        ->orderBy('crime_count', 'desc')
        ->get();
});

function myFunction($arr) {    
    return $arr['delito'];
}
/**
 * RETURNS THE CRIMES TYPES
 */
Route::get('crimes/type', function() {
    $delitos = crimes::select('delito')
    ->where([
        ['delito', '!=', 'delito'],
        ['fecha_hechos', 'like', "2021%"]
        ])
    ->groupBy('delito')
    ->get()
    ->toArray();
    //->get();
    $processed = array_map("myFunction", $delitos);
    return $processed;
});