<?php

namespace Activelogiclabs\Administration\Providers;

use Activelogiclabs\Administration\Admin\Core;
use Activelogiclabs\Administration\Http\ViewComposers\OverviewComponentComposer;
use Activelogiclabs\Administration\Http\ViewComposers\OverviewComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /**
         * Create a formatted date
         */
        Blade::directive('humanReadableDate', function($expression) {
            return "<?php echo {$expression}->format(\"F d, Y @ h:i A\"); ?>";
        });

        /**
         * Build AdminURL
         */
        Blade::directive('adminUrl', function($expression){
            return Core::url(str_replace(['"', "'"], '', $expression));
        });
    }
}