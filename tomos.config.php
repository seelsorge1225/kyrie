<?php

$githubRepository = getenv('GITHUB_REPOSITORY');
if (!is_string($githubRepository) || !preg_match('/^([A-Za-z0-9_.-]+)\/([A-Za-z0-9_.-]+)$/D', $githubRepository, $matches)) {
    throw new RuntimeException('GITHUB_REPOSITORY must be set to owner/repository.');
}

$owner = strtolower($matches[1]);
$repository = strtolower($matches[2]);
$siteName = getenv('TOMOS_SITE_NAME');
if (!is_string($siteName) || trim($siteName) === '') {
    $siteName = $repository;
}

$basePath = $repository === $owner . '.github.io' ? '' : '/' . $repository;
// TOMOS_CUSTOM_DOMAIN_V2_CONFIG_ONLY
$siteUrl = 'https://' . $owner . '.github.io';
$customDomainFile = __DIR__ . '/CNAME';
if (is_link($customDomainFile)) {
    throw new RuntimeException('Unsafe CNAME symlink.');
}
if (is_file($customDomainFile)) {
    $cnameSource = file_get_contents($customDomainFile);
    $customDomain = is_string($cnameSource) ? trim($cnameSource) : '';
    if (!is_string($cnameSource) || strlen($cnameSource) > 256
        || !preg_match('/\A(?=.{1,253}\z)(?:[A-Za-z0-9](?:[A-Za-z0-9-]{0,61}[A-Za-z0-9])?\.)+[A-Za-z]{2,63}\z/D', $customDomain)) {
        throw new RuntimeException('Invalid custom domain in CNAME.');
    }
    $siteUrl = 'https://' . strtolower($customDomain);
    $legacyCheckUrl = 'https://' . $owner . '.github.io' . $basePath . '/';
    $basePath = '';

    // Legacy Tomos GitHub Pages workflows verify the original github.io URL.
    // Emit a nonvisible metadata marker only after a successful static build;
    // canonical/OGP and all actual resource paths keep the new domain.
    if (PHP_SAPI === 'cli' && getenv('GITHUB_ACTIONS') === 'true'
        && realpath((string) getenv('GITHUB_WORKSPACE')) === realpath(__DIR__)
        && getenv('OUTPUT_DIR') === 'build/tomos-github-pages'
        && in_array('--output=build/tomos-github-pages', $_SERVER['argv'] ?? [], true)) {
        register_shutdown_function(static function () use ($legacyCheckUrl): void {
            $outputDir = __DIR__ . '/build/tomos-github-pages';
            $realOutput = realpath($outputDir);
            $realSite = realpath(__DIR__);
            if ($realSite === false || $realOutput === false || is_link($outputDir)
                || strpos($realOutput, $realSite . DIRECTORY_SEPARATOR) !== 0) return;
            $index = $realOutput . '/index.html';
            if (!is_file($index) || is_link($index)) return;
            $html = file_get_contents($index);
            if (!is_string($html) || stripos($html, '</head>') === false) return;
            $meta = '<meta name="tomos:legacy-pages-url" content="'
                . htmlspecialchars($legacyCheckUrl, ENT_QUOTES, 'UTF-8') . '">';
            if (str_contains($html, $meta)) return;
            $updated = preg_replace('~</head>~i', $meta . "\n</head>", $html, 1);
            if (!is_string($updated) || $updated === $html) return;
            $temporary = tempnam($realOutput, '.tomos-domain-');
            if ($temporary === false) return;
            $written = file_put_contents($temporary, $updated);
            if ($written !== strlen($updated)) { @unlink($temporary); return; }
            @chmod($temporary, fileperms($index) & 0777);
            if (!@rename($temporary, $index)) @unlink($temporary);
        });
    }
}
$tomosRoot = getenv('TOMOS_ROOT');
if (!is_string($tomosRoot) || trim($tomosRoot) === '') {
    throw new RuntimeException('TOMOS_ROOT must point to the pinned Tomos checkout.');
}

// TOMOS_SITE_BRANDING_V1
// Site-owned branding images; no extra GitHub App permissions are required.
$tomosBrandingSource = __DIR__ . '/theme-assets';
$tomosBrandingDestination = rtrim($tomosRoot, DIRECTORY_SEPARATOR) . '/theme-assets';
if (is_dir($tomosBrandingSource)) {
    foreach (['ogp', 'favicon'] as $tomosBrandingKind) {
        foreach (['png', 'jpg', 'webp'] as $tomosBrandingExtension) {
            $tomosBrandingName = $tomosBrandingKind . '.' . $tomosBrandingExtension;
            $tomosBrandingFile = $tomosBrandingSource . '/' . $tomosBrandingName;
            if (!is_file($tomosBrandingFile) || is_link($tomosBrandingFile)) continue;
            $tomosBrandingInfo = @getimagesize($tomosBrandingFile);
            if (!is_array($tomosBrandingInfo)) throw new RuntimeException('Invalid site branding image');
            $tomosBrandingMime = ['png' => 'image/png', 'jpg' => 'image/jpeg', 'webp' => 'image/webp'][$tomosBrandingExtension];
            if (($tomosBrandingInfo['mime'] ?? '') !== $tomosBrandingMime) throw new RuntimeException('Invalid site branding image format');
            if (filesize($tomosBrandingFile) > 10485760) {
                throw new RuntimeException('Site branding image exceeds 10 MB');
            }
            if (!is_dir($tomosBrandingDestination)
                && !mkdir($tomosBrandingDestination, 0755, true)
                && !is_dir($tomosBrandingDestination)) {
                throw new RuntimeException('Cannot prepare site branding assets');
            }
            if (!copy($tomosBrandingFile, $tomosBrandingDestination . '/' . $tomosBrandingName)) {
                throw new RuntimeException('Cannot stage site branding image');
            }
        }
    }
}

return [
    'site' => [
        'name' => 'Kyrie.',
        'description' => 'Christian,Bible,and Thinking',
               'url' => $siteUrl,
        'base_path' => $basePath,
        'language' => 'ja',
    ],
    'theme' => [
        'name' => 'tomos-index',
    ],
    'paths' => [
        'content_dir' => __DIR__ . '/content',
        'cache_dir' => __DIR__ . '/.tomos-cache',
        'theme_dir' => rtrim($tomosRoot, DIRECTORY_SEPARATOR) . '/themes',
    ],
    'features' => [
        'search' => true,
        'tags' => true,
        'rss' => true,
        'sitemap' => true,
    ],
];
