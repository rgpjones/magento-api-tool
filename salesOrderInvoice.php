<?php
require 'RemoteService.php';

try {
    $service = new RemoteService();

    $itemQty = array(
        // These are the order item IDs returned in salesOrderInfo - they are sequental 1 & 2
        // here because it's the first order in the system. They are the increment IDs from
        // the database table
        array('order_item_id' => 3, 'qty' => 1),
    );

    $result1 = $service->salesOrderInvoiceCreate(array(
        'invoiceIncrementId' => '100000051',
        'itemsQty' => $itemQty, 
        'comment' => 'Invoice created via script'
    ));
    print_r($result1);

    $result2 = $service->salesOrderInvoiceCapture(array(
        'invoiceIncrementId' => $result1->result
    ));
    print_r($result2);
} catch (Exception $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
