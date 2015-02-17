<?php
require_once __DIR__ . '/../RemoteService.php';

try {
    $service = new RemoteService();

    $result = $service->catalogProductAttributeOptions(array(
        'attributeId' => 'color',
        'storeView' => 'default'
    ));

    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) . "':\n";
    print_r($e);
}
