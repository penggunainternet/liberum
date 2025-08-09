<?php

namespace App\Providers;

use ViewContract;
use App\Models\User;
use App\Models\Category;
use App\Models\View;
use App\Models\Reply;
use App\Models\Views;
use App\Models\Thread;
use App\Contracts\ViewsContract;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ViewsContract::class, Views::class);

        $this->app->bind(ViewContract::class, View::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootEloquentMorphsRelations();

        view()->composer('components.partials.sidenav', function ($view) {
            $view->with('categories', Category::all());
        });

        view()->composer('components.partials.sidenav', function ($view) {
            $view->with('threads', Thread::approved()->get());
        });

        // Share thread counts with admin navigation
        view()->composer('components.admin-navigation', function ($view) {
            $view->with([
                'pendingCount' => Thread::where('status', 'pending')
                    ->whereHas('authorRelation', function($query) {
                        $query->where('type', '!=', 3); // exclude admin threads
                    })->count(),
                'approvedCount' => Thread::where('status', 'approved')
                    ->whereHas('authorRelation', function($query) {
                        $query->where('type', '!=', 3); // exclude admin threads
                    })->count(),
                'rejectedCount' => Thread::where('status', 'rejected')
                    ->whereHas('authorRelation', function($query) {
                        $query->where('type', '!=', 3); // exclude admin threads
                    })->count(),
            ]);
        });
    }

    public function bootEloquentMorphsRelations()
    {
        Relation::morphMap([
            Thread::TABLE => Thread::class,
            Reply::TABLE => Reply::class,
            User::TABLE => User::class,
        ]);
    }
}
