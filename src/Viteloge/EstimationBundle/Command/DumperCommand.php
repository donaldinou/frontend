<?php

namespace Viteloge\EstimationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DumperCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName( 'viteloge:services:dump' )
            ->setDescription( 'Dump service ids' )
            ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        var_dump($this->getcontainer()->getServiceIds());
    }
}
