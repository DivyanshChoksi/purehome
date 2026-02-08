<?php
// ===============================
// CLOUDINARY CONFIG FILE
// ===============================

// Load Composer autoload (Cloudinary SDK)
require_once __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Configuration\Configuration;

// Cloudinary configuration
Configuration::instance([
    'cloud' => [
        'cloud_name' => 'du6emfyhy',
        'api_key'    => '231668212521896',
        'api_secret' => 'LeVY9paOz75LUoMTvivtRdm4Lno',
    ],
    'url' => [
        'secure' => true
    ]
]);
