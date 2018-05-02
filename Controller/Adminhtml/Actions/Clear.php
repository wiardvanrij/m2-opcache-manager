<?php
/**
 * Copyright © 2017 Webfixit.nl. All rights reserved.
 * Wiard van Rij - Webfixit
 */
namespace Webfixit\OpCache\Controller\Adminhtml\Actions;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use \Webfixit\OpCache\Helper;

class Clear extends Action
{

    /**
     * @var Helper\OpCache
     */
    private $opcache;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
        $this->opcache = new Helper\OpCache();
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->opcache->clear();

        if ($result) {
            $this->messageManager->addSuccessMessage('Cleared OpCache successfully');
        } else {
            $this->messageManager->addErrorMessage('Failed to clear; OpCache not present or enabled?');
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());

        return $resultRedirect;
    }
}
