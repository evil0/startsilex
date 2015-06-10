<?php

namespace Services\Command;

use Silex\Application;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/* Callable by using app/console test */

class ExampleCommand extends \Knp\Command\Command
{

    protected function configure() {
        $this
            ->setName("test")
            ->setDescription("A test command!");
        }
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("It works!");
    }
}