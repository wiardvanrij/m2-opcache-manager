<?php
/**
 * Copyright © 2017 Webfixit.nl. All rights reserved.
 * Wiard van Rij - Webfixit
 */
namespace Webfixit\OpCache\Controller\Adminhtml\Actions;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Webfixit\OpCache\Helper\OpCache;

class Clear extends Action
{

    /**
     * @var OpCache
     */
    private $opcache;

    /**
     * Clear constructor.
     *
     * @param Context $context
     * @param OpCache $opCache
     */
    public function __construct(
        Context $context,
        OpCache $opCache
    ) {
        parent::__construct($context);
        $this->opcache = $opCache;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->opcache->clear();

        if ($result) {
            $this->messageManager->addSuccessMessage(__('Cleared OpCache successfully'));
        } else {
            $this->messageManager->addErrorMessage(__('Failed to clear; OpCache not present or enabled?'));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());

        return $resultRedirect;
    }
}
