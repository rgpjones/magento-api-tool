<?php
require_once 'RemoteService.php';

try {
    // Get customer Info by ID
    $service = new RemoteService();
    $result = $service->customerCustomerInfo(array('customerId' => $argv[1]));
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
