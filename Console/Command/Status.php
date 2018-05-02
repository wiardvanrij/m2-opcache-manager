<?php
/**
 * Copyright © 2017 Webfixit.nl. All rights reserved.
 * Wiard van Rij - Webfixit
 */
namespace Webfixit\OpCache\Console\Command;

use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Webfixit\OpCache\Helper;

class Status extends \Symfony\Component\Console\Command\Command
{

    protected function configure()
    {
        $this->setName('opcache:status')
             ->setDescription('Shows the OpCache status');

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
        /** @var Helper\OpCache $opcache */
        $opCache = new Helper\OpCache();
        $result = $opCache->status();
        $output->writeln(print_r($result, true));
    }

}