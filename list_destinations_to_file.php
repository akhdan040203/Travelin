<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$out = "";
foreach(App\Models\Destination::all(['name', 'slug']) as $d) {
    $out .= $d->name . '|' . $d->slug . "\n";
}
file_put_contents('dest_list.txt', $out);
