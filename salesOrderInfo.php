<?php
require_once 'RemoteService.php';

try {
    // Get a sales order information by Magento increment ID
    $service = new RemoteService();
    $result = $service->salesOrderInfo(array('orderIncrementId' => $argv[1]));
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
