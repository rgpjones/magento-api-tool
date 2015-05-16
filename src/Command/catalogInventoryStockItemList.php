<?php
require_once __DIR__ . '/../RemoteService.php';

if (!isset($argv[1])) {
    die('Require products IDs as comma separated list');
}

$productIds = explode(',', $argv[1]);

try {

    $service = new RemoteService();

    $result = $service->catalogInventoryStockItemList(
        [
            'productIds' => $productIds,
        ]
    );

    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
