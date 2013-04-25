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

Route::get('poc', function()
{
	$parser = ARC2::getRDFParser();
	$parser->parse('http://localhost:8890/sparql');
	$triples = $parser->getTriples();
	$data = '';
	foreach ($triples as $triple) {
		$data .= print_r($triple, true) . "\n";
	}
	return "<pre>" . $data . "</pre>";
});