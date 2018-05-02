<?php
namespace Webfixit\OpCache\Controller\Action;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use \Webfixit\OpCache\Helper;

class Clear extends Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_jsonFactory;

    /**
     * @var \Webfixit\OpCache\Helper\OpCache
     */
    private $opcache;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;

    /**
     * Index constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    ) {
        $this->opcache = new Helper\OpCache();
        $this->_jsonFactory = $jsonFactory;
        $this->_objectManager = $context->getObjectManager();
        $this->fileSystem = $this->_objectManager->get('Magento\Framework\Filesystem');

        return parent::__construct($context);
    }

    public function execute()
    {
        $path = $this->fileSystem->getDirectoryRead(DirectoryList::VAR_DIR)->getAbsolutePath();
        $file = $path . '/allow-opcache.flush';

        if (file_exists($file)) {
            $result = $this->opcache->clear();

            if ($result) {
                $message = 'Cleared OpCache successfully';
            } else {
                $message = 'Failed to clear; OpCache not present or enabled?';
            }
        } else {
            $message = 'Not allowed to execute this';
        }

        return $this->_jsonFactory->create()->setData(['result' => $message]);
    }
}