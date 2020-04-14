<?php

Route::namespace('Blog\Module\Http\Controllers')
    ->group(function() {
        Route::post('blog/get-for-front', [
            'as' => 'blog.get-for-front',
            'uses' => 'BlogController@getForFront'
        ]);
    })
;
