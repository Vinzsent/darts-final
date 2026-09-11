<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;

$ctrl = app()->make(App\Http\Controllers\AirconController::class);

// Render create view
try {
    $r = Request::create('/aircons/create', 'GET');
    $html = $ctrl->create()->render();
    echo 'create index supp=Supplier render len=' . strlen($html) . ' hasForm=' . str_contains($html, "save-aircon") . PHP_EOL;
} catch (Throwable $e) {
    echo 'create index FAIL: ' . $e->getMessage() . PHP_EOL;
}

// Render edit view for aircon 1
try {
    $html = $ctrl->edit(1)->render();
    echo 'edit render len=' . strlen($html) . ' hasUpdate=' . str_contains($html, 'Update Aircon') . PHP_EOL;
} catch (Throwable $e) {
    echo 'edit FAIL: ' . $e->getMessage() . PHP_EOL;
}

// Image model accessor
try {
    $img = App\Models\AirconImage::find(1);
    echo 'img url accessor: ' . ($img ? ($img->url ?? 'NULL') : 'no row') . PHP_EOL;
} catch (Throwable $e) {
    echo 'img accessor FAIL: ' . $e->getMessage() . PHP_EOL;
}