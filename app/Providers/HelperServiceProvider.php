<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
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
        $folderHelper = app_path('helpers');
        if (File::isDirectory($folderHelper)) {
            $helperFiles = File::allFiles($folderHelper);
            foreach ($helperFiles as $key => $file) {
                require app_path('helpers/' . $file->getFilename());
            }
        }
    }
}
