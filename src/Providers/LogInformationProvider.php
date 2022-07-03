<?php declare(strict_types=1);
namespace Tonod\LogInformation\Providers;

use Illuminate\Support\ServiceProvider;

class LogInformationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
    }
}
