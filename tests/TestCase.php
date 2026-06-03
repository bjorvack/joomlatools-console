<?php
/**
 * Base Test Case
 * 
 * This class provides common functionality for all test cases.
 */

namespace Joomlatools\Console\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionMethod;

abstract class TestCase extends BaseTestCase
{
    /**
     * Get a private or protected method for testing
     *
     * @param object|string $class Class name or object
     * @param string $methodName Method name
     * @return ReflectionMethod
     */
    protected function getPrivateMethod($class, $methodName)
    {
        $reflection = new ReflectionClass($class);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * Get a private or protected property for testing
     *
     * @param object|string $class Class name or object
     * @param string $propertyName Property name
     * @return mixed
     */
    protected function getPrivateProperty($class, $propertyName)
    {
        $reflection = new ReflectionClass($class);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        
        if (is_object($class)) {
            return $property->getValue($class);
        }
        
        return $property->getValue();
    }

    /**
     * Set a private or protected property for testing
     *
     * @param object $object Object to set property on
     * @param string $propertyName Property name
     * @param mixed $value Value to set
     * @return void
     */
    protected function setPrivateProperty($object, $propertyName, $value)
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    /**
     * Call a private or protected method for testing
     *
     * @param object $object Object to call method on
     * @param string $methodName Method name
     * @param array $parameters Method parameters
     * @return mixed
     */
    protected function callPrivateMethod($object, $methodName, array $parameters = [])
    {
        $method = $this->getPrivateMethod($object, $methodName);
        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Create a temporary file for testing
     *
     * @param string $content File content
     * @param string $prefix File prefix
     * @return string File path
     */
    protected function createTempFile($content = '', $prefix = 'test_')
    {
        $tempDir = TESTS_DIR . '/temp';
        $filePath = tempnam($tempDir, $prefix);
        
        if ($content !== '') {
            file_put_contents($filePath, $content);
        }
        
        return $filePath;
    }

    /**
     * Create a temporary directory for testing
     *
     * @param string $prefix Directory prefix
     * @return string Directory path
     */
    protected function createTempDir($prefix = 'test_dir_')
    {
        $tempDir = TESTS_DIR . '/temp';
        $dirPath = $tempDir . '/' . $prefix . uniqid();
        mkdir($dirPath, 0777, true);
        return $dirPath;
    }

    /**
     * Clean up a temporary file or directory
     *
     * @param string $path Path to clean up
     * @return void
     */
    protected function cleanupTempPath($path)
    {
        if (is_file($path)) {
            @unlink($path);
        } elseif (is_dir($path)) {
            $files = glob($path . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                } elseif (is_dir($file)) {
                    $this->cleanupTempPath($file);
                }
            }
            @rmdir($path);
        }
    }

    /**
     * Get fixture file path
     *
     * @param string $fixturePath Relative path from fixtures directory
     * @return string Full path to fixture file
     */
    protected function getFixturePath($fixturePath)
    {
        return FIXTURES_DIR . '/' . $fixturePath;
    }

    /**
     * Get fixture file content
     *
     * @param string $fixturePath Relative path from fixtures directory
     * @return string File content
     */
    protected function getFixtureContent($fixturePath)
    {
        $filePath = $this->getFixturePath($fixturePath);
        
        if (!file_exists($filePath)) {
            throw new \RuntimeException("Fixture file not found: {$filePath}");
        }
        
        return file_get_contents($filePath);
    }

    /**
     * Assert that a string contains another string
     *
     * @param string $needle Needle to search for
     * @param string $haystack Haystack to search in
     * @param string $message Optional message
     * @return void
     */
    protected function assertStringContains($needle, $haystack, $message = '')
    {
        $this->assertStringContainsString($needle, $haystack, $message);
    }

    /**
     * Assert that a string does not contain another string
     *
     * @param string $needle Needle to search for
     * @param string $haystack Haystack to search in
     * @param string $message Optional message
     * @return void
     */
    protected function assertStringNotContains($needle, $haystack, $message = '')
    {
        $this->assertStringNotContainsString($needle, $haystack, $message);
    }

    /**
     * Set up method called before each test
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Custom setup can be added here
    }

    /**
     * Tear down method called after each test
     *
     * @return void
     */
    protected function tearDown(): void
    {
        // Custom cleanup can be added here
        parent::tearDown();
    }
}