<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$baseCategoryQuery = \App\Models\Category::with(['children' => function($q) {
    $q->where('is_active', true);
}])
->where('is_active', true)
->withCount(['users as providers_count' => function ($query) {
    $query->where('provider_type', 'supplier')->where('active', 'active');
}]);

$cats = (clone $baseCategoryQuery)->where('parent_id', 25)->get();
echo json_encode($cats->toArray());
