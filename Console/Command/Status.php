<?php
/**
 * Copyright © 2017 Webfixit.nl. All rights reserved.
 * Wiard van Rij - Webfixit
 */
namespace Webfixit\OpCache\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webfixit\OpCache\Helper\OpCache;

class Status extends Command
{

    /**
     * @var OpCache
     */
    private $opCacheHelper;

    public function __construct(
        OpCache $opCacheHelper
    ) {
        $this->opCacheHelper = $opCacheHelper;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('opcache:status')->setDescription('Shows the OpCache status');

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->opCacheHelper->status();
        $output->writeln(print_r($result, true));
    }
}
