<?php
require_once __DIR__ . '/../RemoteService.php';

try {
    // Get a sales order information by Magento increment ID
    $service = new RemoteService();

    $orderId = $service->arg(1);
    $status  = $service->arg(2);
    $comment = $service->arg(3);

    $result = $service->salesOrderInfo(array('orderIncrementId' => $orderId));
    if ($result->status == $status) {
        die("Order {$orderId} is already in state '{$status}'\n");
    } else {
        $result = $service->salesOrderAddComment(array(
            'orderIncrementId' => $orderId,
            'status'  => $status,
            'comment' => $comment
        ));
        if ($result) {
            die("Updated {$orderId} to status '{$status}'\n");
        } else {
            die("Order {$orderId} failed to update status.\n");
        }
    }
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
