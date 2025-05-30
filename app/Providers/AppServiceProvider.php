<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blueprint::macro('auditColumns', function () {
            $this->timestamp('created_at')->nullable();
            $this->string('created_by')->nullable();
            $this->timestamp('updated_at')->nullable();
            $this->string('updated_by')->nullable();
            $this->timestamp('deleted_at')->nullable();
            $this->string('deleted_by')->nullable();
        });

    }
}
