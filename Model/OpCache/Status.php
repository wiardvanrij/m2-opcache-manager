<?php
/**
 * Copyright © 2017 Webfixit.nl. All rights reserved.
 * Wiard van Rij - Webfixit
 */
namespace Webfixit\OpCache\Model\OpCache;

use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Webfixit\OpCache\Helper;

class Status extends Command
{
    /**
     * The opcache
     *
     * @var Helper\OpCache
     */
    private $opcache;
    
    protected function configure()
    {
        $this->setName('opcache:status')
             ->setDescription('Show OpCache status');
        
        parent::configure();
        
        $this->opcache = new Helper\OpCache();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->opcache->status();
        $output->writeln(print_r($result, true));
    }
}
