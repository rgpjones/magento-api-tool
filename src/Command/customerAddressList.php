<?php
require_once __DIR__ . '/../RemoteService.php';

try {
    // List customer addresses by customer ID
    $service = new RemoteService();
    $result = $service->customerAddressList(array('customerId' => $argv[1]));
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
