<?php
require_once __DIR__ . '/../RemoteService.php';

try {
    // Get a sales order information by Magento increment ID
    $service = new RemoteService();

    $orderId = $service->arg(1);
    $status  = $service->arg(2);
    $comment = $service->arg(3);

    if (is_null($orderId)) {
        die("Please provide an order increment ID to set the status for\n");
    }
    if (is_null($status)) {
        die("Please provide a status to set\n");
    }
    if (is_null($comment)) {
        $comment = "Order status set to '{$status}' using API";
    }

    $result = $service->salesOrderInfo(array('orderIncrementId' => $orderId));
    if ($result->result->status == $status) {
        die("Order {$orderId} is already in state '{$status}'\n");
    } else {
        $result = $service->salesOrderAddComment(array(
            'orderIncrementId' => $orderId,
            'status'  => $status,
            'comment' => $comment
        ));
        if ($result) {
            echo "Updated {$orderId} to status '{$status}'\n";
        } else {
            echo "Order {$orderId} failed to update status.\n";
        }
    }
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
