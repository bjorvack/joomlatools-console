<?php
/**
 * Joomla 6.1 Version File Sample
 * This is a simplified version for testing purposes
 */

namespace Joomla\CMS;

class Version6_1
{
    const MAJOR_VERSION = '6';
    const MINOR_VERSION = '1';
    const PATCH_VERSION = '0';
    const EXTRA_VERSION = '';
    
    public static function getShortVersion()
    {
        return self::MAJOR_VERSION . '.' . self::MINOR_VERSION . '.' . self::PATCH_VERSION;
    }
    
    public static function getLongVersion()
    {
        return 'Joomla! ' . self::MAJOR_VERSION . '.' . self::MINOR_VERSION . '.' . self::PATCH_VERSION;
    }
}