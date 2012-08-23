<?php
require_once 'RemoteService.php';

try {
    $service = new RemoteService();

    $filters  = array(
        // Get only processing orders
        'filter' => array(
            array('key' => 'status', 'value' => 'processing'),
        ),
        // Get orders made today
        'complex_filter' => array(
            array('key' => 'created_at', 'value' => array('key' => 'gteq', 'value' => date('Y-m-d', strtotime('-1 month'))))
        ),
    );

    // Get a sales order list by the applied filters
    $result = $service->salesOrderList(array('filters' => $filters));
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
