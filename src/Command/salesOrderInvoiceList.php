<?php
require_once __DIR__ . '/../RemoteService.php';

try {
    $service = new RemoteService();

    if (!isset($argv[1])) {
        die("Please provide an Order ID\n");
    }

    $orderId = $argv[1];

    $filters  = array(
        // Get only processing orders
        'filter' => array(
            array('key' => 'order_id', 'value' => $orderId),
        ),
        // Get orders made today
        /*
        'complex_filter' => array(
            array('key' => 'order_id', 'value' => array('key' => 'gteq', 'value' => date('Y-m-d', strtotime('-6 month'))))
        ),
        */
    );

    // Get a sales order list by the applied filters
    $result = $service->salesOrderInvoiceList(array('filters' => $filters));
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
