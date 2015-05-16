<?php
require_once __DIR__ . '/../RemoteService.php';

try {
    $service = new RemoteService();

    $status = $argv[1];

    $filters  = array(
        // Get only processing orders
        'filter' => array(
            array('key' => 'status', 'value' => $status),
        ),
        // Get orders made today
        'complex_filter' => array(
            array('key' => 'created_at', 'value' => array('key' => 'gteq', 'value' => date('Y-m-d', strtotime('-1 week'))))
        ),
    );

    // Get a sales order list by the applied filters
    $result = $service->salesOrderList(array('filters' => $filters));
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
