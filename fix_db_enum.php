<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement("ALTER TABLE tenders MODIFY COLUMN status VARCHAR(255) DEFAULT 'pending_review'");
DB::statement("ALTER TABLE supply_requests MODIFY COLUMN status VARCHAR(255) DEFAULT 'pending'");

echo "Done\n";
