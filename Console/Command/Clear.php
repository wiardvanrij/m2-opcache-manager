<?php
/**
 * Copyright © 2017 Webfixit.nl. All rights reserved.
 * Wiard van Rij - Webfixit
 */
namespace Webfixit\OpCache\Console\Command;

use \Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\App\Filesystem\DirectoryList;
use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem;

class Clear extends \Symfony\Component\Console\Command\Command
{

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * Opcache constructor.
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        Curl $curl
    ) {
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
        $this->curl = $curl;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('opcache:clear')
             ->setDescription('Clears the OpCache');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Symfony\Component\Console\Output\OutputInterface|null
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

        return isset($response['result']) ?:  "Failed to get a result from the OpCache page";

    }

    private function disallowFlush()
    {
        $path = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR)->getAbsolutePath();
        $file = $path . '/allow-opcache.flush';

        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * @return bool|string
     */
    private function allowFlush()
    {
        $path = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR)->getAbsolutePath();
        $file = $path . '/allow-opcache.flush';

        try {
            $fopen = fopen($file, "w");
            fwrite($fopen, date('m/d/Y h:i:s a'));
            fclose($fopen);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }
}
