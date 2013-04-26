<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('/openapi/eldis/search/{object?}/{parameter?}/{query?}',       'LDController@showSearch');

Route::get('/openapi/eldis/count/{object?}/{parameter?}/{query?}',        'LDController@showEcho');

Route::get('/openapi/eldis/get/{object?}/{parameter?}/{query?}',          'LDController@showEcho');

Route::get('/openapi/eldis/get_all/{object?}/{parameter?}/{query?}',      'LDController@showEcho');

Route::get('/openapi/eldis/get_children/{object?}/{parameter?}/{query?}', 'LDController@showEcho');

Route::get('/openapi/eldis/fieldlist/{object?}/{parameter?}/{query?}',    'LDController@showEcho');

///////////////////////////////////////////////////////////////

Route::get('home', 'HomeController@showWelcome');

Route::get('poc', function()
{
    $url = Config::get('sparql.endpoint');
	$parser = ARC2::getRDFParser();
	$parser->parse($url);
	$triples = $parser->getTriples();
	$data = '';
	foreach ($triples as $triple) {
		$data .= print_r($triple, true) . "\n";
	}
	return "<html><body><h1>Hitting $url</h1><pre>" . $data . "</pre></body></html>";
});

Route::get('query', 'POCController@showQuery');


// http://api.ids.ac.uk/openapi/ site / function / data object / parameter / query
// site = eldis for now
// function = search | count | get | get_all | get_children | fieldlist
// objectAssets (Documents | Organisations | Items) | Categories (themes | countries | regions)
// parameter = format (full|short) | all | id | keyword_count | region_count | theme_count
// query

// e.g. search
//