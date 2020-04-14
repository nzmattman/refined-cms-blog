<?php

Route::namespace('Blog\Module\Http\Controllers')
    ->group(function() {
        Route::resource('blog', 'BlogController');
    })
;
