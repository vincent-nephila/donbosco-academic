<?php
require 'ajaxroutes.php';

Route::get('/','HomeController@index');


//Conduct
Route::get('classconduct/{level}/{section}','ConductController@index');
Route::post('saveconduct/{level}/{section}','ConductController@saveconduct');
Route::get('submitconduct/{level}/{section}','ConductController@submitconduct');
Route::get('reopenconduct/{level}/{section}','ConductController@reopenconduct');

Route::get('classconduct/{level}/{section}/import','ConductController@import');
Route::post('importconduct/{level}/{section}','ConductController@viewimport');

//APAA Grade Submission
Route::get('importgrade','APAA\SubmitGrade@index');
Route::post('upload/grade','APAA\SubmitGrade@importgrade');
Route::post('saveentry','APAA\SubmitGrade@saveentry');

//Sheet A
Route::get('gradesheetA/{selectedSy}','SheetA\Grade@index');
Route::get('electivesheetA/{selectedSy}','SheetA\Elective@index');

//Sheet B
Route::get('gradesheetB','SheetB\SheetBController@index');
Route::get('/downloadsheetb/{level}/{strand}/{semester}/{quarter}','SheetB\SheetBController@download');

Route::auth();

