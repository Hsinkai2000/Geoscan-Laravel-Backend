<?php

use Barryvdh\DomPDF\ServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\GlobalFunctionsServiceProvider::class,
    ServiceProvider::class,
];