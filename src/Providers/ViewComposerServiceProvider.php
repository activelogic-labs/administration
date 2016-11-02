<?php
/**
 * Created by Dalton Gibbs
 * Date: 11/1/16
 * Time: 10:42 PM
 */

namespace Activelogiclabs\Administration\Providers;

use Activelogiclabs\Administration\Http\ViewComposers\OverviewComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    public $composers = [
        'administration::overview' => OverviewComposer::class
    ];

    public function boot()
    {
        foreach ($this->composers as $view => $composer) {

            View::composer(
                $view, $composer
            );
        }
    }
}