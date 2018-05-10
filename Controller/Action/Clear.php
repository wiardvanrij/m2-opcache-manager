<?php
namespace Webfixit\OpCache\Controller\Action;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filesystem;
use Webfixit\OpCache\Helper\OpCache;

class Clear extends Action
{
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var OpCache
     */
    private $opcache;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * Clear constructor.
     * @param Context     $context
     * @param JsonFactory $jsonFactory
     * @param OpCache     $opCache
     * @param Filesystem  $filesystem
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        OpCache $opCache,
        Filesystem $filesystem
    ) {
        $this->opcache        = $opCache;
        $this->jsonFactory    = $jsonFactory;
        $this->_objectManager = $context->getObjectManager();
        $this->fileSystem     = $filesystem;

        return parent::__construct($context);
    }

    public function execute()
    {
        $reader = $this->fileSystem->getDirectoryRead(DirectoryList::VAR_DIR);
        $file   = 'allow-opcache.flush';

        if ($reader->isExist($file)) {
            $result = $this->opcache->clear();

            if ($result) {
                $message = __('Cleared OpCache successfully');
            } else {
                $message = __('Failed to clear; OpCache not present or enabled?');
            }
        } else {
            $message = __('Not allowed to execute this');
        }

        return $this->jsonFactory->create()->setData(['result' => $message]);
    }
}
