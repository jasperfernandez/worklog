<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $this->configureCommands();
        $this->configureModels();
        $this->configureDates();
        $this->configurePasswordValidation();
        $this->configureVite();
        $this->configureScheme();
        $this->configureResources();
    }

    /**
     * Configure the application's commands.
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            app()->isProduction()
        );
    }

    /**
     * Configure the models.
     */
    private function configureModels(): void
    {
        Model::shouldBeStrict(!app()->isProduction());
        Model::automaticallyEagerLoadRelationships();
        Model::unguard();
    }

    /**
     * Configure the password validation rules.
     */
    private function configurePasswordValidation(): void
    {
        Password::defaults(fn() => app()->isProduction() ? Password::min(8)->uncompromised() : null);
    }

    /**
     * Configure the dates.
     */
    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Configure vite
     */
    public function configureVite(): void
    {
        Vite::prefetch(concurrency: 3);
    }

    /**
     * Configure application scheme
     */
    public function configureScheme(): void
    {
        if (app()->isProduction()) {
            URL::forceHttps();
        }
    }

    /**
     * Configure resources.
     */
    private function configureResources(): void
    {
        JsonResource::withoutWrapping();
    }
}
