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
Route::get('home', function()
{
	$page_title = "My Home Page Title";
	return View::make('myviews.home')
	->nest('header', 'common.header')
	->nest('footer', 'common.footer')
	->with('title', $page_title);
});
Route::get('second', function()
{
	$view = View::make('myviews.second');
	$view->nest('header', 'common.header')->nest('footer', 'common.footer');
	$view->nest('userinfo', 'common.userinfo', ['my_name' => 'Bionikspoon', 'my_city' => 'Chicago']);
	return $view;
});
Route::get('blade-home', function()
{
	$movies = [
		[ 'name' => 'Star Wars', 'year' => '1977', 'slug' => 'star-wars'],
		[ 'name' => 'The Matrix', 'year' => '1999', 'slug' => 'matrix'],
		[ 'name' => 'Die Hard', 'year' => '1988', 'slug' => 'die-hard'],
		[ 'name' => 'Clerks', 'year' => '1994', 'slug' => 'clerks'],
	];
	return View::make('blade.home')->with('movies', $movies);
});
Route::get('blade-second/{slug}', function($slug)
{
	$movies = [
		'star-wars' => [ 'name' => 'Star Wars', 'year' => '1977', 'genre' => 'Sci-fi'],
		'matrix' => [ 'name' => 'The Matrix', 'year' => '1999', 'genre' => 'Sci-fi'],
		'die-hard' => ['name' => 'Die Hard', 'year' => '1988', 'genre' => 'Action'],
		'clerks' => ['name' =>'Clerks', 'year' => '1994', 'genre' => 'Comedy'],
	];
	return View::make('blade.second')->with('movie', $movies[$slug]);
});

Route::get('twig-view', function()
{
	$link = HTML::link('http://laravel.com', 'the laravel site.');
	return View::make('twig')->with('link', $link);
});
Route::get('choose', function()
{
	return View::make('language.choose');
});
Route::post('choose', function()
{
	Session::put('lang', Input::get('language'));
	return Redirect::to('localized');
});
Route::get('localized', function()
{
	$lang = Session::get('lang', function()
	{
		return 'en';
	});
	App::setLocale($lang);
	return View::make('language.localized');
});
Route::get('localized-german', function()
{
	App::setLocale('de');
	return View::make('language.localized-german');
});
Route::get('menu-one', function()
{
	return View::of('layout')
		->nest('content', 'menu-one');
});
Route::get('menu-two', function()
{
	return View::of('layout')
		->nest('content', 'menu-two');
});
Route::get('menu-three', function()
{
	return View::of('layout')
		->nest('content', 'menu-three');
});
Route::get('boot', function()
{
	$superheroes = ['Batman', 'Superman', 'Wolverine', 'Deadpool', 'Iron Man'];
	return View::make('boot')->with('superheroes', $superheroes);
});
View::name('menu-layout', 'layout');
View::composer('menu-layout', function($view)
{
	$view->with('style', HTML::style('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css'));
	$view->nest('menu', 'menu-menu');
	$view->with('page_title', 'View Composer Title');
});
