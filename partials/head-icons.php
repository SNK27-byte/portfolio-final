<?php
$icoDir = __DIR__ . '/../images/ico';
$icoUrl = '/images/ico';
$logoUrl = '/images/LogoLP.png';

$icoFiles = [
    'favicon.ico' => ['rel' => 'icon', 'type' => 'image/x-icon', 'sizes' => ''],
    'favicon-96x96.png' => ['rel' => 'icon', 'type' => 'image/png', 'sizes' => '96x96'],
    'favicon-32x32.png' => ['rel' => 'icon', 'type' => 'image/png', 'sizes' => '32x32'],
    'favicon-16x16.png' => ['rel' => 'icon', 'type' => 'image/png', 'sizes' => '16x16'],
    'favicon.png' => ['rel' => 'icon', 'type' => 'image/png', 'sizes' => ''],
    'apple-touch-icon.png' => ['rel' => 'apple-touch-icon', 'type' => 'image/png', 'sizes' => ''],
    'apple-touch-icon-57.png' => ['rel' => 'apple-touch-icon', 'type' => 'image/png', 'sizes' => '57x57'],
    'apple-touch-icon-72.png' => ['rel' => 'apple-touch-icon', 'type' => 'image/png', 'sizes' => '72x72'],
    'apple-touch-icon-76.png' => ['rel' => 'apple-touch-icon', 'type' => 'image/png', 'sizes' => '76x76'],
    'apple-touch-icon-114.png' => ['rel' => 'apple-touch-icon', 'type' => 'image/png', 'sizes' => '114x114'],
    'apple-touch-icon-120.png' => ['rel' => 'apple-touch-icon', 'type' => 'image/png', 'sizes' => '120x120'],
    'apple-touch-icon-144.png' => ['rel' => 'apple-touch-icon', 'type' => 'image/png', 'sizes' => '144x144'],
    'apple-touch-icon-152.png' => ['rel' => 'apple-touch-icon', 'type' => 'image/png', 'sizes' => '152x152'],
    'web-app-manifest-192x192.png' => ['rel' => 'icon', 'type' => 'image/png', 'sizes' => '192x192'],
    'web-app-manifest-512x512.png' => ['rel' => 'icon', 'type' => 'image/png', 'sizes' => '512x512'],
];

$hasIcoPack = is_file($icoDir . '/favicon.ico') || is_file($icoDir . '/apple-touch-icon.png');

if ($hasIcoPack) {
    foreach ($icoFiles as $file => $meta) {
        if (!is_file($icoDir . '/' . $file)) {
            continue;
        }

        $href = htmlspecialchars($icoUrl . '/' . $file, ENT_QUOTES, 'UTF-8');
        $type = htmlspecialchars($meta['type'], ENT_QUOTES, 'UTF-8');
        $sizes = $meta['sizes'] !== '' ? ' sizes="' . htmlspecialchars($meta['sizes'], ENT_QUOTES, 'UTF-8') . '"' : '';

        echo '<link rel="' . $meta['rel'] . '" type="' . $type . '" href="' . $href . '"' . $sizes . '>' . PHP_EOL;
    }
} else {
    echo '<link rel="icon" type="image/png" href="' . htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') . '" sizes="32x32">' . PHP_EOL;
    echo '<link rel="shortcut icon" href="' . htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') . '" type="image/png">' . PHP_EOL;
    echo '<link rel="apple-touch-icon" href="' . htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') . '">' . PHP_EOL;
}
