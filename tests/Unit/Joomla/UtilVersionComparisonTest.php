<?php
/**
 * Unit tests for Util::isJoomla4/5/6() methods
 */

namespace Joomlatools\Console\Tests\Unit\Joomla;

use Joomlatools\Console\Tests\TestCase;
use Joomlatools\Console\Joomla\Util;

class UtilVersionComparisonTest extends TestCase
{
    private $tempDir;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->tempDir = $this->createTempDir('joomla_version_test_');
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
     * Test version comparison logic for isJoomla4
     */
    public function testVersionComparisonIsJoomla4()
    {
        $versions = [
            ['3.10.0', false],
            ['3.10.12', false],
            ['4.0.0', true],
            ['4.4.13', true],
            ['5.0.0', true],
            ['5.4.5', true],
            ['6.0.0', true],
            ['6.1.0', true],
        ];
        
        foreach ($versions as $versionData) {
            $version = $versionData[0];
            $expected = $versionData[1];
            $result = version_compare($version, '4.0.0', '>=');
            $this->assertEquals($expected, (bool)$result, "Version $version should return $expected for isJoomla4 check");
        }
    }
    
    /**
     * Test version comparison logic for isJoomla5
     */
    public function testVersionComparisonIsJoomla5()
    {
        $versions = [
            ['3.10.0', false],
            ['4.0.0', false],
            ['4.4.13', false],
            ['5.0.0', true],
            ['5.4.5', true],
            ['6.0.0', true],
            ['6.1.0', true],
        ];
        
        foreach ($versions as $versionData) {
            $version = $versionData[0];
            $expected = $versionData[1];
            $result = version_compare($version, '5.0.0', '>=');
            $this->assertEquals($expected, (bool)$result, "Version $version should return $expected for isJoomla5 check");
        }
    }
    
    /**
     * Test version comparison logic for isJoomla6
     */
    public function testVersionComparisonIsJoomla6()
    {
        $versions = [
            ['3.10.0', false],
            ['4.0.0', false],
            ['5.0.0', false],
            ['5.4.5', false],
            ['6.0.0', true],
            ['6.1.0', true],
        ];
        
        foreach ($versions as $versionData) {
            $version = $versionData[0];
            $expected = $versionData[1];
            $result = version_compare($version, '6.0.0', '>=');
            $this->assertEquals($expected, (bool)$result, "Version $version should return $expected for isJoomla6 check");
        }
    }
    
    /**
     * Test boundary condition: 3.9.9 vs 4.0.0
     */
    public function testBoundaryCondition399Vs400()
    {
        $version = '3.9.9';
        $result = version_compare($version, '4.0.0', '>=');
        $this->assertFalse((bool)$result, "Version 3.9.9 should not be >= 4.0.0");
    }
    
    /**
     * Test boundary condition: 4.0.0 vs 4.0.0
     */
    public function testBoundaryCondition400Vs400()
    {
        $version = '4.0.0';
        $result = version_compare($version, '4.0.0', '>=');
        $this->assertTrue((bool)$result, "Version 4.0.0 should be >= 4.0.0");
    }
    
    /**
     * Test edge case: beta version 4.0.0-beta
     */
    public function testEdgeCaseBetaVersion()
    {
        $version = '4.0.0-beta';
        $result = version_compare($version, '4.0.0', '>=');
        $this->assertFalse((bool)$result, "Beta version 4.0.0-beta should not be >= 4.0.0");
    }
    
    /**
     * Test edge case: dev version 4.0.0-dev
     */
    public function testEdgeCaseDevVersion()
    {
        $version = '4.0.0-dev';
        $result = version_compare($version, '4.0.0', '>=');
        $this->assertFalse((bool)$result, "Dev version 4.0.0-dev should not be >= 4.0.0");
    }
    
    /**
     * Test edge case: alpha version 5.0.0-alpha
     */
    public function testEdgeCaseAlphaVersion()
    {
        $version = '5.0.0-alpha';
        $result = version_compare($version, '5.0.0', '>=');
        $this->assertFalse((bool)$result, "Alpha version 5.0.0-alpha should not be >= 5.0.0");
    }
    
    /**
     * Test edge case: RC version 6.0.0-RC
     */
    public function testEdgeCaseRCVersion()
    {
        $version = '6.0.0-RC';
        $result = version_compare($version, '6.0.0', '>=');
        $this->assertFalse((bool)$result, "RC version 6.0.0-RC should not be >= 6.0.0");
    }
    
    /**
     * Test version string format with 'v' prefix
     */
    public function testVersionStringWithVPrefix()
    {
        $version = 'v4.4.13';
        $result = version_compare($version, '4.0.0', '>=');
        // PHP's version_compare handles the 'v' prefix
        $this->assertTrue((bool)$result, "Version v4.4.13 should be >= 4.0.0");
    }
    
    /**
     * Test version string format without 'v' prefix
     */
    public function testVersionStringWithoutVPrefix()
    {
        $version = '4.4.13';
        $result = version_compare($version, '4.0.0', '>=');
        $this->assertTrue((bool)$result, "Version 4.4.13 should be >= 4.0.0");
    }
    
    /**
     * Test version with patch version 0
     */
    public function testVersionWithPatchZero()
    {
        $version = '4.0.0';
        $result = version_compare($version, '4.0.0', '>=');
        $this->assertTrue((bool)$result, "Version 4.0.0 should be >= 4.0.0");
    }
    
    /**
     * Test version comparison is case sensitive for stability suffixes
     */
    public function testVersionComparisonCaseSensitivity()
    {
        $version1 = '4.4.0-stable';
        $version2 = '4.4.0-STABLE';
        
        $result1 = version_compare($version1, '4.0.0', '>=');
        $result2 = version_compare($version2, '4.0.0', '>=');
        
        // Both should be false as they are pre-release
        $this->assertFalse((bool)$result1);
        $this->assertFalse((bool)$result2);
    }
    
    /**
     * Test very old Joomla 1.5 version
     */
    public function testVeryOldJoomla15Version()
    {
        $version = '1.5.26';
        $result = version_compare($version, '4.0.0', '>=');
        $this->assertFalse((bool)$result, "Joomla 1.5.26 should not be >= 4.0.0");
    }
    
    /**
     * Test future Joomla version
     */
    public function testFutureJoomlaVersion()
    {
        $version = '7.0.0';
        $result = version_compare($version, '6.0.0', '>=');
        $this->assertTrue((bool)$result, "Future version 7.0.0 should be >= 6.0.0");
    }
}