<?php

Route::get('/getsubmittersubjs/{access}','APAA\Helper@getsubmittersubjects');
Route::get('/sheetAelectivesection/{action?}','SheetA\Helper@electivesection');
Route::get('/sheetAelectivelist','SheetA\Helper@sheetAelectivelist');

Route::get('/getlevelstrands/{action?}','SheetA\Helper@getlevelstrands');
Route::get('/getlevelsections/{all}/{action?}','SheetA\Helper@getlevelsections');
Route::get('/getlevelsubjects/{access}/{action?}','SheetA\Helper@getlevelsubjects');
Route::get('/getlevelquarter/{action?}','Helper@getQuarter');

Route::get('/gradeSheetAList','SheetA\Helper@gradeSheetAList');


Route::get('/gradeSheetBList','SheetB\SheetBController@gradeSheetBList');




