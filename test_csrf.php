<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/login', 'GET');
$response = $kernel->handle($request);
// extract csrf token from response content
preg_match('/name="_token" value="([^"]+)"/', $response->getContent(), $matches);
$token = $matches[1] ?? null;

echo "Token: $token\n";
