<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

// Create a trainer
$user = \App\Models\User::factory()->create(['role' => 'trainer']);
\Illuminate\Support\Facades\Auth::login($user);

$request = Illuminate\Http\Request::create('/trainer/dashboard', 'GET');
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
