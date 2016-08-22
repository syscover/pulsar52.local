## Implementation of Pulsar packages

Add to composer.json this packages inside require array:
```
"syscover/nav-tools": "~1.0",
"syscover/pulsar": "~1.0",
"syscover/forms": "~1.0",
"syscover/market": "~1.0",
"syscover/comunik": "~1.0",
"syscover/booking": "~1.0",
"syscover/hotels": "~1.0",
"syscover/spas": "~1.0",
"syscover/wineries": "~1.0",
"syscover/cms": "~1.0",
"syscover/factura-directa": "~1.0"
```

Execute on console to load all base files of Laravel Framework:
```
composer update --no-scripts
```

Replace in config/app.php this services providers:
```
/*
 * Pulsar Application Service Providers...
 */
App\Providers\NavToolsServiceProvider::class,
App\Providers\PulsarServiceProvider::class,
App\Providers\OctopusServiceProvider::class,
App\Providers\HotelsServiceProvider::class,
App\Providers\SpasServiceProvider::class,
App\Providers\WineriesServiceProvider::class,
App\Providers\BookingServiceProvider::class,
App\Providers\ComunikServiceProvider::class,
App\Providers\CmsServiceProvider::class,
App\Providers\CrmServiceProvider::class,
App\Providers\MarketServiceProvider::class,
App\Providers\FormsServiceProvider::class,
App\Providers\ShoppingCartServiceProvider::class,
App\Providers\ProjectsServiceProvider::class,
App\Providers\FetchServiceProvider::class,
App\Providers\FacturaDirectaServiceProvider::class,
```

by this others:
```
/*
 * Pulsar Application Service Providers...
 */
Syscover\NavTools\NavToolsServiceProvider::class,
Syscover\Pulsar\PulsarServiceProvider::class,
Syscover\Hotels\HotelsServiceProvider::class,
Syscover\Spas\SpasServiceProvider::class,
Syscover\Wineries\WineriesServiceProvider::class,
Syscover\Booking\BookingServiceProvider::class,
Syscover\Comunik\ComunikServiceProvider::class,
Syscover\Cms\CmsServiceProvider::class,
Syscover\Crm\CrmServiceProvider::class,
Syscover\Market\MarketServiceProvider::class,
Syscover\Forms\FormsServiceProvider::class,
Syscover\ShoppingCart\ShoppingCartServiceProvider::class,
Syscover\FacturaDirecta\FacturaDirectaServiceProvider::class,
```