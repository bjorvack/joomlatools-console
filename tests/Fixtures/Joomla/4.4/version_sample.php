<?php
/**
 * Joomla 4.4 Version File Sample
 * This is a simplified version for testing purposes
 */

namespace Joomla\CMS;

class Version4_4
{
    const RELEASE = '4';
    const DEV_LEVEL = '4';
    const MAJOR_VERSION = '4';
    const MINOR_VERSION = '4';
    const PATCH_VERSION = '13';
    const EXTRA_VERSION = '';
    
    public static function getShortVersion()
    {
        return self::RELEASE . '.' . self::DEV_LEVEL;
    }
    
    public static function getLongVersion()
    {
        return 'Joomla! ' . self::MAJOR_VERSION . '.' . self::MINOR_VERSION . '.' . self::PATCH_VERSION;
    }
}