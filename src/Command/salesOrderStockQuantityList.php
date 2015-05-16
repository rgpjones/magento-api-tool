<?php
require_once __DIR__ . '/../RemoteService.php';

try {
    $service = new RemoteService();

    $params  = [
        // Get only processing orders
        'orderStatuses' => ['processing'],
    ];

    // Get a sales order list by the applied filters
    $result = $service->salesOrderStockQuantityList($params);
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
