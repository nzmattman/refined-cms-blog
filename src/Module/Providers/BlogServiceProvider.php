<?php

namespace RefinedDigital\Blog\Module\Providers;

use Illuminate\Support\ServiceProvider;
use RefinedDigital\Blog\Commands\Install;
use RefinedDigital\CMS\Modules\Core\Models\PackageAggregate;
use RefinedDigital\CMS\Modules\Core\Models\ModuleAggregate;
use RefinedDigital\CMS\Modules\Core\Models\RouteAggregate;

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
            __DIR__.'/../Resources/views',
            app_path().'/views'
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Install::class,
            ]);
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


        $this->mergeConfigFrom(__DIR__.'/../../../config/blog.php', 'blog');

        $menuConfig = [
            'order' => 2,
            'name' => 'Blog',
            'icon' => 'fas fa-comment',
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
