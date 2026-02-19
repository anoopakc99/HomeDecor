<?php
echo "PHP Version: " . phpversion() . "<br>";
echo "Loaded Configuration File: " . php_ini_loaded_file() . "<br>";
echo "GD Extension Loaded: " . (extension_loaded('gd') ? 'Yes' : 'No') . "<br>";

if (extension_loaded('gd')) {
    echo "GD Info: <pre>" . print_r(gd_info(), true) . "</pre>";
} else {
    echo "Extension Dir: " . ini_get('extension_dir') . "<br>";
}
