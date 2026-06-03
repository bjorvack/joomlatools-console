<?php
/**
 * Joomla 3.10 Version File Sample
 * This is a simplified version for testing purposes
 */

class JVersion3_10
{
    public $RELEASE = '3';
    public $DEV_LEVEL = '10';
    
    public function getShortVersion()
    {
        return $this->RELEASE . '.' . $this->DEV_LEVEL;
    }
    
    public function getLongVersion()
    {
        return 'Joomla! ' . $this->RELEASE . '.' . $this->DEV_LEVEL;
    }
}