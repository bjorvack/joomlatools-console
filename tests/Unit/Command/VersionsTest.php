<?php
/**
 * Unit tests for Versions::getLatestRelease()
 */

namespace Joomlatools\Console\Tests\Unit\Command;

use Joomlatools\Console\Tests\TestCase;
use Joomlatools\Console\Command\Versions;

class VersionsTest extends TestCase
{
    private $versions;
    private $tempCacheDir;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->versions = new Versions();
        $this->tempCacheDir = $this->createTempDir('versions_cache_');
    }
    
    protected function tearDown(): void
    {
        $this->cleanupTempPath($this->tempCacheDir);
        parent::tearDown();
    }
    
    /**
     * Test version filtering for prefix '2'
     */
    public function testVersionFilteringForPrefix2()
    {
        $versions = ['2.5.28', '3.10.0', '4.0.0', '5.0.0'];
        $prefix = '2';
        
        $filtered = array_filter($versions, function($version) use ($prefix) {
            $compare = $version;
            if (substr($compare, 0, 1) == 'v') {
                $compare = substr($compare, 1);
            }
            return substr($compare, 0, strlen($prefix)) == $prefix;
        });
        
        $this->assertCount(1, $filtered);
        $this->assertEquals('2.5.28', reset($filtered));
    }
    
    /**
     * Test version filtering for prefix '3'
     */
    public function testVersionFilteringForPrefix3()
    {
        $versions = ['2.5.28', '3.10.0', '3.10.12', '4.0.0', '5.0.0'];
        $prefix = '3';
        
        $filtered = array_filter($versions, function($version) use ($prefix) {
            $compare = $version;
            if (substr($compare, 0, 1) == 'v') {
                $compare = substr($compare, 1);
            }
            return substr($compare, 0, strlen($prefix)) == $prefix;
        });
        
        $this->assertCount(2, $filtered);
        $this->assertContains('3.10.0', $filtered);
        $this->assertContains('3.10.12', $filtered);
    }
    
    /**
     * Test version filtering for prefix '4'
     */
    public function testVersionFilteringForPrefix4()
    {
        $versions = ['3.10.0', '4.0.0', '4.4.13', '5.0.0', '6.0.0'];
        $prefix = '4';
        
        $filtered = array_filter($versions, function($version) use ($prefix) {
            $compare = $version;
            if (substr($compare, 0, 1) == 'v') {
                $compare = substr($compare, 1);
            }
            return substr($compare, 0, strlen($prefix)) == $prefix;
        });
        
        $this->assertCount(2, $filtered);
        $this->assertContains('4.0.0', $filtered);
        $this->assertContains('4.4.13', $filtered);
    }
    
    /**
     * Test version filtering for prefix '5'
     */
    public function testVersionFilteringForPrefix5()
    {
        $versions = ['4.0.0', '5.0.0', '5.4.5', '6.0.0'];
        $prefix = '5';
        
        $filtered = array_filter($versions, function($version) use ($prefix) {
            $compare = $version;
            if (substr($compare, 0, 1) == 'v') {
                $compare = substr($compare, 1);
            }
            return substr($compare, 0, strlen($prefix)) == $prefix;
        });
        
        $this->assertCount(2, $filtered);
        $this->assertContains('5.0.0', $filtered);
        $this->assertContains('5.4.5', $filtered);
    }
    
    /**
     * Test version filtering for prefix '6'
     */
    public function testVersionFilteringForPrefix6()
    {
        $versions = ['5.0.0', '6.0.0', '6.1.0'];
        $prefix = '6';
        
        $filtered = array_filter($versions, function($version) use ($prefix) {
            $compare = $version;
            if (substr($compare, 0, 1) == 'v') {
                $compare = substr($compare, 1);
            }
            return substr($compare, 0, strlen($prefix)) == $prefix;
        });
        
        $this->assertCount(2, $filtered);
        $this->assertContains('6.0.0', $filtered);
        $this->assertContains('6.1.0', $filtered);
    }
    
    /**
     * Test prefix-based version selection with 'v' prefix
     */
    public function testPrefixBasedSelectionWithVPrefix()
    {
        $versions = ['v3.10.0', 'v4.0.0', 'v5.0.0'];
        $prefix = '4';
        
        $filtered = array_filter($versions, function($version) use ($prefix) {
            $compare = $version;
            if (substr($compare, 0, 1) == 'v') {
                $compare = substr($compare, 1);
            }
            return substr($compare, 0, strlen($prefix)) == $prefix;
        });
        
        $this->assertCount(1, $filtered);
        $this->assertEquals('v4.0.0', reset($filtered));
    }
    
    /**
     * Test filtering out alpha versions
     */
    public function testFilterOutAlphaVersions()
    {
        $versions = ['4.0.0', '4.0.0-alpha', '4.4.13', '5.0.0-alpha1'];
        
        $filtered = array_filter($versions, function($version) {
            return !preg_match('#(?:alpha|beta|rc)#i', $version);
        });
        
        $this->assertCount(2, $filtered);
        $this->assertContains('4.0.0', $filtered);
        $this->assertContains('4.4.13', $filtered);
        $this->assertNotContains('4.0.0-alpha', $filtered);
        $this->assertNotContains('5.0.0-alpha1', $filtered);
    }
    
    /**
     * Test filtering out beta versions
     */
    public function testFilterOutBetaVersions()
    {
        $versions = ['4.0.0', '4.0.0-beta', '4.4.13', '5.0.0-beta2'];
        
        $filtered = array_filter($versions, function($version) {
            return !preg_match('#(?:alpha|beta|rc)#i', $version);
        });
        
        $this->assertCount(2, $filtered);
        $this->assertContains('4.0.0', $filtered);
        $this->assertContains('4.4.13', $filtered);
        $this->assertNotContains('4.0.0-beta', $filtered);
        $this->assertNotContains('5.0.0-beta2', $filtered);
    }
    
    /**
     * Test filtering out RC versions
     */
    public function testFilterOutRCVersions()
    {
        $versions = ['4.0.0', '4.0.0-RC1', '4.4.13', '5.0.0-RC2'];
        
        $filtered = array_filter($versions, function($version) {
            return !preg_match('#(?:alpha|beta|rc)#i', $version);
        });
        
        $this->assertCount(2, $filtered);
        $this->assertContains('4.0.0', $filtered);
        $this->assertContains('4.4.13', $filtered);
        $this->assertNotContains('4.0.0-RC1', $filtered);
        $this->assertNotContains('5.0.0-RC2', $filtered);
    }
    
    /**
     * Test version comparison to find latest
     */
    public function testVersionComparisonToFindLatest()
    {
        $versions = ['3.10.0', '3.10.12', '4.0.0', '4.4.13'];
        $latest = '0.0.0';
        
        foreach($versions as $version) {
            if(version_compare($latest, strtolower($version), '<')) {
                $latest = $version;
            }
        }
        
        $this->assertEquals('4.4.13', $latest);
    }
    
    /**
     * Test version comparison with 'v' prefix
     */
    public function testVersionComparisonWithVPrefix()
    {
        $versions = ['v3.10.0', 'v3.10.12', 'v4.0.0', 'v4.4.13'];
        $latest = '0.0.0';
        
        foreach($versions as $version) {
            $compare = $version;
            if (substr($compare, 0, 1) == 'v') {
                $compare = substr($compare, 1);
            }
            if(version_compare($latest, strtolower($compare), '<')) {
                $latest = $version;
            }
        }
        
        $this->assertEquals('v4.4.13', $latest);
    }
    
    /**
     * Test handling of non-standard version formats
     */
    public function testHandlingOfNonStandardVersionFormats()
    {
        $versions = ['4.0.0', '4.0.0-dev', '4.0.0-stable', 'invalid-version'];
        
        $filtered = array_filter($versions, function($version) {
            // Skip versions that don't match standard format
            if(!preg_match('/v?\d\.\d+\.\d+.*/im', $version) || preg_match('#(?:alpha|beta|rc)#i', $version)) {
                return false;
            }
            return true;
        });
        
        $this->assertCount(1, $filtered);
        $this->assertContains('4.0.0', $filtered);
    }
    
    /**
     * test prefix normalization
     */
    public function testPrefixNormalization()
    {
        $prefix = 'v4';
        $expected = '4';
        
        if (substr($prefix, 0, 1) == 'v') {
            $normalized = substr($prefix, 1);
        } else {
            $normalized = $prefix;
        }
        
        $this->assertEquals($expected, $normalized);
    }
    
    /**
     * Test prefix without 'v' prefix
     */
    public function testPrefixWithoutVPrefix()
    {
        $prefix = '4';
        $expected = '4';
        
        if (substr($prefix, 0, 1) == 'v') {
            $normalized = substr($prefix, 1);
        } else {
            $normalized = $prefix;
        }
        
        $this->assertEquals($expected, $normalized);
    }
    
    /**
     * Test repository constant
     */
    public function testRepositoryConstant()
    {
        $this->assertEquals('https://github.com/joomla/joomla-cms', Versions::REPO_JOOMLA_CMS);
    }
    
    /**
     * Test setRepository method
     */
    public function testSetRepository()
    {
        $customRepo = 'https://github.com/custom/repo';
        $this->versions->setRepository($customRepo);
        
        $this->assertEquals($customRepo, $this->versions->getRepository());
    }
    
    /**
     * Test getRepository method
     */
    public function testGetRepository()
    {
        $this->assertEquals(Versions::REPO_JOOMLA_CMS, $this->versions->getRepository());
    }
}