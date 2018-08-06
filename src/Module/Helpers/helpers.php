<?php

use \RefinedDigital\Blog\Module\Http\Repositories\BlogRepository;

if (! function_exists('blog')) {
    function blog()
    {
        return app(BlogRepository::class);
    }
}
