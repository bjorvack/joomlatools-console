<?php
/**
 * PHPUnit Bootstrap File
 * 
 * This file is loaded before all tests and sets up the test environment.
 */

// Set error reporting to maximum for testing
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('memory_limit', '512M');

// Set test environment
define('APP_ENV', 'testing');
define('APP_DEBUG', true);

// Load Composer autoloader
$autoloadPath = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    die(
        'You must set up the project dependencies first. Run:' . PHP_EOL .
        '    composer install' . PHP_EOL
    );
}

require $autoloadPath;

// Set up test-specific autoloading for test files
spl_autoload_register(function ($class) {
    // Map test classes to test files
    $prefix = 'Joomlatools\\Console\\Tests\\';
    $baseDir = __DIR__;
    
    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }
    
    // Get the relative class name
    $relativeClass = substr($class, $len);
    
    // Replace namespace separators with directory separators
    $relativeClass = str_replace('\\', '/', $relativeClass);
    
    // Construct the file path
    $file = $baseDir . '/' . $relativeClass . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Set default timezone (required by some PHP functions)
date_default_timezone_set('UTC');

// Configure test environment variables
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=array');

// Set up test-specific constants if needed
define('TESTS_DIR', __DIR__);
define('FIXTURES_DIR', __DIR__ . '/Fixtures');
define('MOCKS_DIR', __DIR__ . '/Mocks');

// Clean up any temporary files from previous test runs
if (file_exists(__DIR__ . '/temp')) {
    $files = glob(__DIR__ . '/temp/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
} else {
    mkdir(__DIR__ . '/temp', 0777, true);
}

// Register a shutdown function for cleanup
register_shutdown_function(function () {
    // Clean up temporary files
    $tempDir = __DIR__ . '/temp';
    if (is_dir($tempDir)) {
        $files = glob($tempDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }
});

echo "Test environment initialized" . PHP_EOL;
echo "PHP Version: " . PHP_VERSION . PHP_EOL;
echo "PHPUnit Bootstrap loaded" . PHP_EOL;