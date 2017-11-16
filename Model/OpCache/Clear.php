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

class Clear extends Command
{
    
    /**
     * The opcache
     *
     * @var Helper\OpCache
     */
    private $opcache;
    
    protected function configure()
    {
        $this->setName('opcache:clear')
             ->setDescription('clear opcache');
        
        parent::configure();
        
        $this->opcache = new Helper\OpCache();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->opcache->clear();
        if ($result) {
            $output->writeln('Cleared OpCache');
        } else {
            $output->writeln('OpCache not enabled');
        }
    }
}
