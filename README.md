## Implementation of Pulsar packages

Add to composer.json this packages inside require array:
```
"syscover/nav-tools": "~1.0",
"syscover/pulsar": "~1.0",
"syscover/forms": "~1.0",
"syscover/market": "~1.0",
"syscover/comunik": "~1.0",
"syscover/cms": "~1.0",
"syscover/factura-directa": "~1.0"
```

Execute on console to load all base files of Laravel Framework:
```
composer install --no-scripts
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
Syscover\Comunik\ComunikServiceProvider::class,
Syscover\Cms\CmsServiceProvider::class,
Syscover\Crm\CrmServiceProvider::class,
Syscover\Market\MarketServiceProvider::class,
Syscover\Forms\FormsServiceProvider::class,
Syscover\ShoppingCart\ShoppingCartServiceProvider::class,
Syscover\FacturaDirecta\FacturaDirectaServiceProvider::class,
```

After execute on console to publish:
```
php artisan vendor:publish --force
```

Config your .env file with database connection and execute migrations:
```
php artisan migrate
```

Execute optimize
```
php artisan optimize
```

And execute seeds
```
php artisan db:seed --class="PulsarTableSeeder"
php artisan db:seed --class="ComunikTableSeeder"
php artisan db:seed --class="CmsTableSeeder"
php artisan db:seed --class="CrmTableSeeder"
php artisan db:seed --class="MarketTableSeeder"
php artisan db:seed --class="FormsTableSeeder"

```

When the installation is complete you can access these data

url: http://www.your-domain.com/pulsar
user: admin@pulsar.local
pasword: 123456