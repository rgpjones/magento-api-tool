<?php

namespace Rgpjones\MagentoApiTool\Command\Service;

use Inviqa\Command\Command;
use Rgpjones\MagentoApiTool\Service\ServiceContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    private $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer)
    {
        parent::__construct();
        $this->serviceContainer = $serviceContainer;
    }

    public function configure()
    {
        $this
            ->setName('service:list')
            ->setDescription('List the service configurations');
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->serviceContainer->listServices() as $service) {
            $output->writeln($service);
        }
    }
}