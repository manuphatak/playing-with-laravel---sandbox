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
Route::get('userform', function()
{
	return View::make('userform');
});
Route::post('userform', function()
{
	$rules = array(
		'email' => 'required|email|different:username',
		'username' => 'required|min:6',
		'password' => 'required|same:password_confirm'
		);
	$validation = Validator::make(Input::all(), $rules);
	if ($validation->fails())
	{
		return Redirect::to('userform')->withErrors($validation)->withInput();
	}
});
Route::get('userresults', function()
{
	return dd(Input::old());
});
Route::get('fileform', function()
{
	return View::make('fileform');
});
Route::post('fileform', function()
{
	$rules = array('myfile' => 'mimes:doc,docx,pdf,txt|max:1000');
	$validation = Validator::make(Input::all(), $rules);
	if ($validation->fails()) {
		return Redirect::to('fileform')->withErrors($validation)->withInput();
	} else {
		$file = Input::file('myfile');
		if ($file->move('files', $file->getClientOriginalName()))
		{
			return 'Success';
		}
		else
		{
			return 'Error';
		}
	}
});
Route::get('myform', function()
{
	return View::make('myform');
});
Route::post('myform', array( 'before' => 'csrf', function(){
	$rules = array(
		'email' => 'required|email',
		'username' => 'required',
		'password' => 'required',
		'no_email' => 'honey_pot'
		);
	$messages = array(
		'honey_pot' => 'Nothing should be in this field.'
		);
	$validation = Validator::make(Input::all(), $rules, $messages);
	if ($validation->fails()) {
		return Redirect::to('myform')->withErrors($validation)->withInput();
	}
	return Redirect::to('myresults')->withInput();
}));
Route::get('myresults', function()
{
	return dd(Input::old());
});
Route::get('redactor', function()
{
	return View::make('redactor');
});
Route::post('redactorupload', function()
{
	$rules = array(
		'file' => 'image|max:10000'
		);
	$validation = Validator::make(Input::all(), $rules);
	$file = Input::file('file');
	if ($validation->fails())
	{
		return FALSE;
	}
	else
	{
		if ($file->move('files', $file->getClientOriginalName()))
		{
			return Response::json(array('filelink' => 'files/' . $file->getClientOriginalName()));
		}
		else
		{
			return FALSE;
		}
	}	

});
Route::post('redactor', function()
{
	return dd(Input::all());
});
Route::get('imageform', function()
{
	return View::make('imageform');
});
Route::post('imageform', function()
{
	$rules = array(
		'image' => 'required|mimes:jpeg,jpg|max:10000'
		);
	$validation = Validator::make(Input::all(), $rules);

	if ($validation->fails()) {
		return Redirect::to('imageform')->withErrors($validation);
	}
	else {
		$file = Input::file('image');
		$file_name = $file->getClientOriginalName();
		if ($file->move('images', $file_name)) {
			return Redirect::to('jcrop')->with('image', $file_name);
		} else {
			return "Error uploading file";
		}

	}
});
Route::get('jcrop', function()
{
	return View::make('jcrop')->with('image', 'images/' . Session::get('image'));
});
Route::post('jcrop', function()
{
	$quality = 90;
	$src = Input::get('image');
	$img = imagecreatefromjpeg($src);
	$dest = imagecreatetruecolor(Input::get('w'), Input::get('h'));

	imagecopyresampled($dest, $img, 0, 0, Input::get('x'), Input::get('y'), Input::get('w'), Input::get('h'), Input::get('w'), Input::get('h'));
	imagejpeg($dest, $src, $quality);
	return "<img src='$src' >";
});
Route::get('autocomplete', function()
{
	return View::make('autocomplete');
});
Route::get('getdata', function()
{
	$term = Str::lower(Input::get('term'));
	$data = array(
		'R' => 'Red', 
		'O' => 'Orange', 
		'Y' => 'Yellow', 
		'G' => 'Green', 
		'B' => 'Blue', 
		'I' => 'Indigo', 
		'V' => 'Violet', 
		);
	$return_array = array();
	foreach ($data as $key => $value) {
		if (strpos(Str::lower($value), $term) !== FALSE) {
			$return_array[] = array(
				'value' => $value, 
				'id' => $key);
		}
	}
	return Response::json($return_array);
});
Route::get('captcha', function()
{
	$captcha = new Captcha;
	$cap = $captcha->make();
	return View::make('captcha')->with('cap', $cap);
});
Route::post('captcha', function()
{
	if (Session::get('my_captcha') !== Input::get('captcha')) {
		Session::flash('captcha_result', 'No Match.');
	} else {
		Session::flash('captcha_result', 'They Match!');
	}
	return Redirect::to('captcha');
});