<?php
/**
 * Copyright © 2017 Webfixit.nl. All rights reserved.
 * Wiard van Rij - Webfixit
 */
namespace Webfixit\OpCache\Block\Adminhtml\Cache;


class OpCacheButton extends \Magento\Backend\Block\Template
{
    /**
     * OpCacheButton constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(\Magento\Backend\Block\Template\Context $context, array $data)
    {
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getClearOpCacheUrl()
    {
        return $this->getUrl('opcache/actions/clear');
    }
}