<?php
// ===============================
// CLOUDINARY CONFIG (FINAL)
// ===============================

// Hide deprecated warnings (Cloudinary + PHP 8.1+)
error_reporting(E_ALL & ~E_DEPRECATED);

// Composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Configuration\Configuration;

// Cloudinary configuration via ENV
Configuration::instance([
    'cloud' => [
        'cloud_name' => getenv('CLOUDINARY_CLOUD_NAME'),
        'api_key'    => getenv('CLOUDINARY_API_KEY'),
        'api_secret' => getenv('CLOUDINARY_API_SECRET'),
    ],
    'url' => [
        'secure' => true
    ]
]);
