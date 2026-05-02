<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
foreach(App\Models\Destination::all(['name', 'slug']) as $d) {
    echo $d->name . '|' . $d->slug . PHP_EOL;
}
