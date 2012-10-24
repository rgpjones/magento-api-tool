<?php
require_once 'RemoteService.php';

if (!isset($argv[1])) {
    die('Please provide order increment ID' . PHP_EOL);
}

$orderIncrementId = $argv[1];

try {
    $service = new RemoteService();

    $order = $service->salesOrderInfo(array('orderIncrementId' => $orderIncrementId));

    $items = array();
    foreach ($order->items as $item) {
        echo "[{$item->sku}] {$item->name} £{$item->row_invoiced} - ";
        $qty = (int) $service->ask("Qty to Refund [{$item->qty_invoiced}]", 'text', $item->qty_invoiced);

        if ($qty > $item->qty_invoiced || $qty < 0) {
            die('Invalid Quantity' . PHP_EOL);
        }

        if ($qty >= 0) {
            $cmItem = new stdClass;
            $cmItem->order_item_id = $item->item_id;
            $cmItem->qty = $qty;
            $items[] = $cmItem;
        }
    }

    if (empty($items)) {
        die('No items to refund' . PHP_EOL);
    }

    $shipping = 0;
    if ($order->shipping_amount > 0) {
        $shipping = (float) $service->ask("Refund Shipping £[{$order->shipping_amount}]", 'text', $order->shipping_amount);
    }

    $refund = (float) $service->ask("Goodwill Refund £0.00", 'text', 0);

    $data = new stdClass;
    $data->qtys = $items;
    $data->shipping_amount = $shipping;
    $data->adjustment_negative = 0; // "Penalty" amount
    $data->adjustment_positive = $refund; // "Good will gesture" amount

    $result = $service->salesOrderCreditmemoCreate(array(
        'creditmemoIncrementId'     => $orderIncrementId,
        'creditmemoData'            => $data,
        'comment'                   => 'Test Refund',
        'notifyCustomer'            => 0,
        'includeComment'            => 0,
        'refundToStoreCreditAmount' => ''
    ));

    print_r($result);
    echo PHP_EOL;
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) . "':\n";
    print_r($e);
}
