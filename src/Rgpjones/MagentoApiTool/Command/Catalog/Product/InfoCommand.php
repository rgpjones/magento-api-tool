<?php

namespace Rgpjones\MagentoApiTool\Command\Catalog\Product;

use Rgpjones\MagentoApiTool\Service\RemoteService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InfoCommand extends Command
{
    private $service;

    public function __construct(RemoteService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function configure()
    {
        $this
            ->setName('catalog:product:info')
            ->setDescription('Retrieve information for a product.')
            ->addArgument('productId', InputArgument::REQUIRED, 'ID of product to retrieve')
            ->addArgument('storeId', InputArgument::OPTIONAL, 'ID of store for specific detail');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->service->catalogProductInfo(
            [
                'productId' => $input->getArgument('productId'),
                'storeId' => $input->getArgument('storeId')
            ]
        );

        $output->write(print_r($result, 1));
    }
}