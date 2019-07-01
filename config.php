<?php

require_once __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

return [
    'death_star_secret' => getenv('DEATH_STAR_SECRET'),
    'death_star_id' => getenv('DEATH_STAR_ID'),
    'death_star_url' => getenv('DEATH_STAR_URL'),
];