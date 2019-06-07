<?php

namespace Rgpjones\MagentoApiTool\Command\Sales\Order;

use Rgpjones\MagentoApiTool\Service\RemoteService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddCommentCommand extends Command
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
            ->setName('sales:order:add-comment')
            ->setDescription('Add comment to an order, changing the status if applicable.')
            ->addArgument('orderIncrementId', InputArgument::REQUIRED, 'Increment ID of order to retrieve')
            ->addArgument('status', InputArgument::REQUIRED, 'Status to set the order to')
            ->addArgument('comment', InputArgument::OPTIONAL, 'Comment to add')
            ->addOption('skip-status-check', null, InputOption::VALUE_NONE, false);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $orderId = $input->getArgument('orderIncrementId');
        $status  = $input->getArgument('status');
        $comment = $input->hasArgument('comment')
            ? $input->getArgument('comment')
            : sprintf('Order status set to "%s" using API', $status);

        if (!$input->getOption('skip-status-check')) {
            $result = $this->service->salesOrderInfo(array('orderIncrementId' => $orderId));

            if (isset($result->result)) {
                $result = $result->result;
            }

            if ($result->status == $status) {
                throw new \RuntimeException(sprintf('Order #%s is already in state "%s"', $orderId, $status));
            }
        }

        $result = $this->service->salesOrderAddComment(array(
            'orderIncrementId' => $orderId,
            'status'  => $status,
            'comment' => $comment
        ));

        if ($result) {
            $output->writeln(sprintf('Updated order #%s to status "%s"', $orderId, $status));
        } else {
            $output->writeln(sprintf("Order #%s failed to update status.", $orderId));
        }
    }
}
