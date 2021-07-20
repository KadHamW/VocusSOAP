<?php

namespace KadHamW\VocusSOAP;

use Illuminate\Support\ServiceProvider;

class VocusSOAPServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->config["filesystems.disks.protected"] = [
            'driver' => 'local',
            'root' => storage_path('protected'),
        ];
        $this->mergeConfigFrom(__DIR__.'/../config/VocusSOAP.php', 'vocussoap');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/VocusSOAP.php' => config_path('vocussoap.php'),
            ], 'config');
    }
}
