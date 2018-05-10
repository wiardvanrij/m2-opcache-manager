<?php
/**
 * Copyright © 2017 Webfixit.nl. All rights reserved.
 * Wiard van Rij - Webfixit
 */
namespace Webfixit\OpCache\Block\Adminhtml\Cache;

class OpCacheButton extends \Magento\Backend\Block\Template
{
    /**
     * @return string
     */
    public function getClearOpCacheUrl()
    {
        return $this->getUrl('opcache/actions/clear');
    }
}
