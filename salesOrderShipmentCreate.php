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
        $qty = (int) $service->ask("Qty to Ship [{$item->qty_invoiced}]", 'text', $item->qty_invoiced);

        if ($qty > $item->qty_invoiced || $qty < 0) {
            die('Invalid Quantity' . PHP_EOL);
        }

        if ($qty > 0) {
            $shipItem = new stdClass;
            $shipItem->order_item_id = $item->item_id;
            $shipItem->qty = $qty;
            $items[] = $shipItem;
        }
    }

    if (empty($items)) {
        die('No items to ship' . PHP_EOL);
    }

    $result = $service->salesOrderShipmentCreate(array(
        'orderIncrementId' => $orderIncrementId,
        'itemsQty'         => $items,
        'comment'          => 'Test Shipment',
        'email'            => 1,
        'includeComment'   => 1
    ));

    print_r($result);
    echo PHP_EOL;
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) . "':\n";
    print_r($e);
}
