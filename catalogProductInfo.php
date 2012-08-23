<?php
require_once 'RemoteService.php';

try {
    // Get a sales order information by Magento increment ID
    $service = new RemoteService();
    $result = $service->catalogProductInfo(array('productId' => $argv[1], 'storeId' => $argv[2]));
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
