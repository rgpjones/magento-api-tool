<?php

namespace Rgpjones\MagentoApiTool\Command\Sales\Order;

use Rgpjones\MagentoApiTool\Service\RemoteService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
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
            ->setName('sales:order:list')
            ->setDescription('List orders in a specific state')
            ->addArgument('status', InputArgument::REQUIRED, 'Status to retrieve orders for');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $status = $input->getArgument('status');

        $filters  = array(
            // Get only processing orders
            'filter' => array(
                array('key' => 'status', 'value' => $status),
            ),
            // Get orders made today
            'complex_filter' => array(
                array('key' => 'created_at', 'value' => array('key' => 'gteq', 'value' => date('Y-m-d 08:00:00', strtotime('-8 day')))),
            ),
        );

        // Get a sales order list by the applied filters
        $result = $this->service->salesOrderList(
            [
                'filters' => $filters
            ]
        );

        $output->write(print_r($result, 1));
    }
}
