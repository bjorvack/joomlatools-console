<?php
/**
 * Joomla 5.4 Version File Sample
 * This is a simplified version for testing purposes
 */

namespace Joomla\CMS;

class Version5_4
{
    const MAJOR_VERSION = '5';
    const MINOR_VERSION = '4';
    const PATCH_VERSION = '5';
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