<?php
require_once 'RemoteService.php';

try {
    $service = new RemoteService();

    $result = $service->catalogProductAttributeAddOption(array(
        'attribute' => 'color',
        'data' => array(
            'label' => array(
                array('store_id' => array(0), 'value' => 'Blue'),
            ),
            'order' => 0,
            'is_default' => 0
        ))
    );
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) . "':\n";
    print_r($e);
}
