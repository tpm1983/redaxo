<?php

/** @var rex_addon $this */

$content = '<h2>Media Manager Addon (Version ' . $this->getVersion() . ')</h2>';

ob_start();
require __DIR__ . '/../help.php';
$content .= ob_get_contents();
ob_end_clean();

echo rex_view::contentBlock($content, '', 'tab');
