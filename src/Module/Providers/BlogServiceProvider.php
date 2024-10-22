<?php

namespace RefinedDigital\Blog\Module\Providers;

use Illuminate\Support\ServiceProvider;
use RefinedDigital\Blog\Commands\Install;
use RefinedDigital\CMS\Modules\Core\Aggregates\PackageAggregate;
use RefinedDigital\CMS\Modules\Core\Aggregates\ModuleAggregate;
use RefinedDigital\CMS\Modules\Core\Aggregates\PublicRouteAggregate;
use RefinedDigital\CMS\Modules\Core\Aggregates\RouteAggregate;

class BlogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->addNamespace('blog', [
            base_path().'/resources/views',
            __DIR__.'/../Resources/views',
        ]);

        try {
            if ($this->app->runningInConsole()) {
                if (\DB::connection()->getDatabaseName() && !\Schema::hasTable('blogs')) {
                    $this->commands([
                        Install::class,
                    ]);
                }
            }
        } catch(\Exception $e) {

        }

        $this->publishes([
            __DIR__.'/../../../config/blog.php' => config_path('blog.php'),
        ], 'blog');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        app(RouteAggregate::class)
            ->addRouteFile('blog', __DIR__.'/../Http/routes.php');
        app(PublicRouteAggregate::class)
            ->addRouteFile('blog', __DIR__.'/../Http/public-routes.php');


        $this->mergeConfigFrom(__DIR__.'/../../../config/blog.php', 'blog');

        $menuConfig = [
            'order' => 200,
            'name' => 'Blog',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" fill="currentColor"><path class="fa-secondary" opacity=".4" d="M231.5 383C348.9 372.9 448 288.3 448 176c0-5.2-.2-10.4-.6-15.5C555.1 167.1 640 243.2 640 336c0 38.6-14.7 74.3-39.6 103.4c3.5 9.4 8.7 17.7 14.2 24.7c4.8 6.2 9.7 11 13.3 14.3c1.8 1.6 3.3 2.9 4.3 3.7c.5 .4 .9 .7 1.3 1c5.6 4.1 7.9 11.3 5.8 17.9c-2.1 6.6-8.3 11.1-15.2 11.1c-21.8 0-43.8-5.6-62.1-12.5c-9.2-3.5-17.8-7.4-25.3-11.4C505.9 503.3 470.2 512 432 512c-95.6 0-176.2-54.6-200.5-129z"/><path class="fa-primary" d="M416 176c0 97.2-93.1 176-208 176c-38.2 0-73.9-8.7-104.7-23.9c-7.5 4-16 7.9-25.2 11.4C59.8 346.4 37.8 352 16 352c-6.9 0-13.1-4.5-15.2-11.1s.2-13.8 5.8-17.9c0 0 0 0 0 0s0 0 0 0l.2-.2c.2-.2 .6-.4 1.1-.8c1-.8 2.5-2 4.3-3.7c3.6-3.3 8.5-8.1 13.3-14.3c5.5-7 10.7-15.4 14.2-24.7C14.7 250.3 0 214.6 0 176C0 78.8 93.1 0 208 0S416 78.8 416 176z"/></svg>',
            'route' => 'blog',
            'activeFor' => ['blog']
        ];

        app(ModuleAggregate::class)
            ->addMenuItem($menuConfig);

        app(PackageAggregate::class)
            ->addPackage('Blog', [
                'repository' => \RefinedDigital\Blog\Module\Http\Repositories\BlogRepository::class,
                'model' => '\\RefinedDigital\\Blog\\Module\\Models\\Blog',
            ]);
    }
}
