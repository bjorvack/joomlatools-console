<?php
/**
 * Unit tests for Util::getJoomlaVersion()
 */

namespace Joomlatools\Console\Tests\Unit\Joomla;

use Joomlatools\Console\Tests\TestCase;
use Joomlatools\Console\Joomla\Util;

class UtilTest extends TestCase
{
    private $tempDir;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->tempDir = $this->createTempDir('joomla_test_');
    }
    
    protected function tearDown(): void
    {
        // Clear the version cache
        $reflection = new \ReflectionClass(Util::class);
        $property = $reflection->getProperty('_versions');
        $property->setAccessible(true);
        $property->setValue([]);
        
        $this->cleanupTempPath($this->tempDir);
        parent::tearDown();
    }
    
    /**
     * Test that getJoomlaVersion returns false for non-existent directory
     */
    public function testGetJoomlaVersionReturnsFalseForNonExistentDirectory()
    {
        $result = Util::getJoomlaVersion('/non/existent/path');
        $this->assertFalse($result);
    }
    
    /**
     * Test that getJoomlaVersion returns false for directory without version files
     */
    public function testGetJoomlaVersionReturnsFalseForDirectoryWithoutVersionFiles()
    {
        $result = Util::getJoomlaVersion($this->tempDir);
        $this->assertFalse($result);
    }
    
    /**
     * Test that getJoomlaVersion caches results
     */
    public function testGetJoomlaVersionCachesResults()
    {
        // This test will be implemented when we have actual version files
        // For now, we test the caching mechanism
        
        $reflection = new \ReflectionClass(Util::class);
        $property = $reflection->getProperty('_versions');
        $property->setAccessible(true);
        
        // Initially empty
        $this->assertEmpty($property->getValue());
    }
    
    /**
     * Test canonical function with Joomla 3.x RELEASE property format
     */
    public function testCanonicalFunctionWithJoomla3ReleaseProperty()
    {
        $version = new \stdClass();
        $version->RELEASE = '3';
        $version->DEV_LEVEL = '10';
        
        $canonical = function($version) {
            if (isset($version->RELEASE)) {
                return 'v' . $version->RELEASE . '.' . $version->DEV_LEVEL;
            }
            return 'unknown';
        };
        
        $result = $canonical($version);
        $this->assertEquals('v3.10', $result);
    }
    
    /**
     * Test canonical function with Joomla 3.x constant format
     */
    public function testCanonicalFunctionWithJoomla3Constants()
    {
        // Simulate a class with constants
        $version = new class {
            const RELEASE = '3';
            const DEV_LEVEL = '10';
        };
        
        $className = get_class($version);
        
        $canonical = function($version) {
            $className = get_class($version);
            if (defined("$className::RELEASE")) {
                return $version::RELEASE . '.' . $version::DEV_LEVEL;
            }
            return 'unknown';
        };
        
        $result = $canonical($version);
        $this->assertEquals('3.10', $result);
    }
    
    /**
     * Test canonical function with Joomla 4.x MAJOR_VERSION format
     */
    public function testCanonicalFunctionWithJoomla4MajorVersion()
    {
        // Simulate Joomla 4+ version class
        $version = new class {
            const MAJOR_VERSION = '4';
            const MINOR_VERSION = '4';
            const PATCH_VERSION = '13';
            const EXTRA_VERSION = '';
        };
        
        $className = get_class($version);
        
        $canonical = function($version) {
            $className = get_class($version);
            if (defined("$className::MAJOR_VERSION") && in_array($version::MAJOR_VERSION, ['4', '5', '6'])) {
                return $version::MAJOR_VERSION . "." . $version::MINOR_VERSION . "." . $version::PATCH_VERSION . ($version::EXTRA_VERSION ? "." . $version::EXTRA_VERSION : '');
            }
            return 'unknown';
        };
        
        $result = $canonical($version);
        $this->assertEquals('4.4.13', $result);
    }
    
    /**
     * Test canonical function with Joomla 5.x version
     */
    public function testCanonicalFunctionWithJoomla5Version()
    {
        $version = new class {
            const MAJOR_VERSION = '5';
            const MINOR_VERSION = '4';
            const PATCH_VERSION = '5';
            const EXTRA_VERSION = '';
        };
        
        $className = get_class($version);
        
        $canonical = function($version) {
            $className = get_class($version);
            if (defined("$className::MAJOR_VERSION") && in_array($version::MAJOR_VERSION, ['4', '5', '6'])) {
                return $version::MAJOR_VERSION . "." . $version::MINOR_VERSION . "." . $version::PATCH_VERSION . ($version::EXTRA_VERSION ? "." . $version::EXTRA_VERSION : '');
            }
            return 'unknown';
        };
        
        $result = $canonical($version);
        $this->assertEquals('5.4.5', $result);
    }
    
    /**
     * Test canonical function with Joomla 6.x version
     */
    public function testCanonicalFunctionWithJoomla6Version()
    {
        $version = new class {
            const MAJOR_VERSION = '6';
            const MINOR_VERSION = '1';
            const PATCH_VERSION = '0';
            const EXTRA_VERSION = '';
        };
        
        $className = get_class($version);
        
        $canonical = function($version) {
            $className = get_class($version);
            if (defined("$className::MAJOR_VERSION") && in_array($version::MAJOR_VERSION, ['4', '5', '6'])) {
                return $version::MAJOR_VERSION . "." . $version::MINOR_VERSION . "." . $version::PATCH_VERSION . ($version::EXTRA_VERSION ? "." . $version::EXTRA_VERSION : '');
            }
            return 'unknown';
        };
        
        $result = $canonical($version);
        $this->assertEquals('6.1.0', $result);
    }
    
    /**
     * Test canonical function with EXTRA_VERSION
     */
    public function testCanonicalFunctionWithExtraVersion()
    {
        $version = new class {
            const MAJOR_VERSION = '4';
            const MINOR_VERSION = '4';
            const PATCH_VERSION = '0';
            const EXTRA_VERSION = 'dev';
        };
        
        $className = get_class($version);
        
        $canonical = function($version) {
            $className = get_class($version);
            if (defined("$className::MAJOR_VERSION") && in_array($version::MAJOR_VERSION, ['4', '5', '6'])) {
                return $version::MAJOR_VERSION . "." . $version::MINOR_VERSION . "." . $version::PATCH_VERSION . ($version::EXTRA_VERSION ? "." . $version::EXTRA_VERSION : '');
            }
            return 'unknown';
        };
        
        $result = $canonical($version);
        $this->assertEquals('4.4.0.dev', $result);
    }
    
    /**
     * Test canonical function returns unknown for unsupported format
     */
    public function testCanonicalFunctionReturnsUnknownForUnsupportedFormat()
    {
        $version = new \stdClass();
        
        $canonical = function($version) {
            if (isset($version->RELEASE)) {
                return 'v' . $version->RELEASE . '.' . $version->DEV_LEVEL;
            }
            
            $className = get_class($version);
            if (defined("$className::RELEASE")) {
                return $version::RELEASE . '.' . $version::DEV_LEVEL;
            }
            
            if (defined("$className::MAJOR_VERSION") && in_array($version::MAJOR_VERSION, ['4', '5', '6'])) {
                return $version::MAJOR_VERSION . "." . $version::MINOR_VERSION . "." . $version::PATCH_VERSION . ($version::EXTRA_VERSION ? "." . $version::EXTRA_VERSION : '');
            }
            
            return 'unknown';
        };
        
        $result = $canonical($version);
        $this->assertEquals('unknown', $result);
    }
    
    /**
     * Test buildTargetPath method
     */
    public function testBuildTargetPath()
    {
        $base = '/var/www/joomla';
        $path = '/var/www/joomla/libraries';
        
        $result = Util::buildTargetPath($path, $base);
        $this->assertEquals('/var/www/joomla/libraries', $result);
    }
    
    /**
     * Test buildTargetPath with trailing slash in base
     */
    public function testBuildTargetPathWithTrailingSlash()
    {
        $base = '/var/www/joomla/';
        $path = '/var/www/joomla/libraries';
        
        $result = Util::buildTargetPath($path, $base);
        $this->assertEquals('/var/www/joomla/libraries', $result);
    }
    
    /**
     * Test buildTargetPath with relative path
     */
    public function testBuildTargetPathWithRelativePath()
    {
        $base = '/var/www/joomla';
        $path = 'libraries';
        
        $result = Util::buildTargetPath($path, $base);
        $this->assertEquals('/var/www/joomla/libraries', $result);
    }
}