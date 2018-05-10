<?php
/**
 * Copyright © 2017 Webfixit.nl. All rights reserved.
 * Wiard van Rij - Webfixit
 */
namespace Webfixit\OpCache\Console\Command;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Clear extends Command
{
    const FILENAME_OPCACHE_FLUSH = 'allow-opcache.flush';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Curl
     */
    private $curl;

    /**
     * Clear constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param Filesystem            $filesystem
     * @param Curl                  $curl
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        Curl $curl
    ) {
        $this->filesystem   = $filesystem;
        $this->storeManager = $storeManager;
        $this->curl         = $curl;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('opcache:clear')->setDescription('Clears the OpCache');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->clearRequest());
    }

    /**
     * @return string
     */
    private function clearRequest()
    {
        $allowed = $this->allowFlush();

        if ($allowed !== true) {
            return 'Could not write to the var folder. Exception: ' . $allowed;
        }

        $baseUrl = $this->storeManager->getStore()->getBaseUrl();

        // Ignore cert for development env via Valet
        if (strpos($baseUrl, '.dev') !== false) {
            $this->curl->setOption(CURLOPT_SSL_VERIFYHOST, 0);
            $this->curl->setOption(CURLOPT_SSL_VERIFYPEER, 0);
        }

        try {
            $this->curl->get($baseUrl . 'opcacheclear/action/clear');
            $response = json_decode($this->curl->getBody(), true);
        } catch (\Exception $e) {
            $this->disallowFlush();

            return "Failed to query the OpCache page. Exception: " . $e->getMessage();
        }

        $this->disallowFlush();

        return isset($response['result']) ? $response['result'] : "Failed to get a result from the OpCache page";
    }

    /**
     * @return bool|string
     */
    private function allowFlush()
    {
        try {
            $writer = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
            $file = $writer->openFile(self::FILENAME_OPCACHE_FLUSH, 'w');
            try {
                $file->lock();
                try {
                    $file->write(date('m/d/Y h:i:s a'));
                }
                finally {
                    $file->unlock();
                }
            }
            finally {
                $file->close();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @return bool|string
     */
    private function disallowFlush()
    {
        try {
            $writer = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
            if ($writer->isExist(self::FILENAME_OPCACHE_FLUSH)) {
                $writer->delete(self::FILENAME_OPCACHE_FLUSH);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }
}
