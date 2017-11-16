<?php
/**
 * Copyright © 2017 Webfixit.nl. All rights reserved.
 * Wiard van Rij - Webfixit
 */
namespace Webfixit\OpCache\Helper;

class OpCache
{
    /**
     * @return bool
     */
    public function clear()
    {
        $result = opcache_reset();
        
        return $result;
    }
    
    
    public function status()
    {
        $result = opcache_get_status(false);
        
        return $result;
    }
    
}
