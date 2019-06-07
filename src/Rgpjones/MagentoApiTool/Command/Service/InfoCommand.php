<?php

namespace Rgpjones\MagentoApiTool\Command\Service;

use Inviqa\Command\Command;
use Rgpjones\MagentoApiTool\Service\ServiceContainer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InfoCommand extends Command
{
    const ARG_SERVICE_NAME = 'serviceName';

    private $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer)
    {
        parent::__construct();
        $this->serviceContainer = $serviceContainer;
    }

    public function configure()
    {
        $this
            ->setName('service:info')
            ->setDescription('Show the info for the specified service configuration.')
            ->addArgument(self::ARG_SERVICE_NAME, InputArgument::REQUIRED, 'The service name to show information for');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $serviceName = $input->getArgument(self::ARG_SERVICE_NAME);
        $serviceInfo = $this->serviceContainer->getServiceConfiguration($serviceName);

        $output->writeln('SOAP Address: ' . $serviceInfo->getSoapAddress());
        $output->writeln('User: ' . $serviceInfo->getUser());
        $output->writeln('Password: ' . $serviceInfo->getMaskedPassword());
        $output->writeln('WSI: ' . ($serviceInfo->isWsiComplianceEnabled() ? 'Enabled' : 'Disabled'));
    }
}