<?php
require_once __DIR__ . '/../RemoteService.php';

if (!isset($argv[1])) {
    die('Please provide order increment ID' . PHP_EOL);
}

$orderIncrementId = $argv[1];

try {
    $service = new RemoteService();

    $order = $service->salesOrderInfo(array('orderIncrementId' => $orderIncrementId));

    $invoice = $service->salesOrderInvoiceList(
        [
            'filters' => [
                'filter' => [
                    [
                        'key' => 'order_id',
                        'value' => $order->result->order_id
                    ]
                ]
            ]
        ]
    );

    $invoiceIncrementId = $invoice->result->complexObjectArray->increment_id;

    $items = array();
    foreach ($order->result->items->complexObjectArray as $item) {
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

            $rrItem = new stdClass;
            $rrItem->order_item_id = $item->item_id;
            $rrItem->return_code = 1;
            $rrs[] = $rrItem;
        }
    }

    if (empty($items)) {
        die('No items to refund' . PHP_EOL);
    }

    $shipping = 0;
    if ($order->result->shipping_amount > 0) {
        $shipping = $order->shipping_amount + $order->shipping_tax_amount;
        $shipping = (float) $service->ask("Refund Shipping £[{$shipping}]", 'text', $shipping);
    }

    $refund = (float) $service->ask("Goodwill Refund £0.00", 'text', 0);

    $data = new stdClass;
    $data->qtys = $items;
    $data->shipping_amount = $shipping;
    $data->adjustment_negative = 0; // "Penalty" amount
    $data->adjustment_positive = $refund; // "Good will gesture" amount
    $data->invoice_increment_id = $invoiceIncrementId;
    $data->return_to_stock = 1;
    $data->item_reasons = $rrs;

    $result = $service->salesOrderCreditmemoCreate(array(
        'orderIncrementId'          => $orderIncrementId,
        'creditmemoData'            => $data,
        'comment'                   => 'Test Refund',
        'notifyCustomer'            => 0,
        'includeComment'            => 0,
        'refundToStoreCreditAmount' => ''
    ));

    echo "Creditmemo created";
    echo PHP_EOL;
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) . "':\n";
    print_r($e);
}
