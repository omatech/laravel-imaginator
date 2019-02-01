<?php

Route::domain(config('imaginator.server'))->group(function () {
    Route::get(config('imaginator.url_prefix').'/{path}', 'Omatech\Imaginator\Controllers\ImaginatorController@get')
         ->where('path', '.*')->middleware('glideSecurity');
});
