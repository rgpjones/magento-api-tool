<?php

namespace Rgpjones\MagentoApiTool\Command\Sales\Order;

use Rgpjones\MagentoApiTool\Service\RemoteService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InfoCommand extends Command
{
    /**
     * @var RemoteService
     */
    private $service;

    public function __construct(RemoteService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function configure()
    {
        $this
            ->setName('sales:order:info')
            ->setDescription('Retrieve information for an order.')
            ->addArgument('orderIncrementId', InputArgument::REQUIRED, 'Increment ID of order to retrieve');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->service->salesOrderInfo(
            [
                'orderIncrementId' => $input->getArgument('orderIncrementId'),
            ]
        );

        $output->write(print_r($result, 1));
    }
}


