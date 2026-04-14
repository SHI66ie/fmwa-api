<?php
require_once 'config.php';
require_once 'includes/helpers.php';

$keys = ['minister_image', 'perm_sec_image', 'site_logo'];
echo "Current Settings in Database:\n";
foreach ($keys as $key) {
    echo "$key: " . get_setting($key) . "\n";
}
