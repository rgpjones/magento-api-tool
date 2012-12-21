<?php
require_once 'RemoteService.php';

try {
    $service = new RemoteService();
    $service->execute();

} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) . "':\n";
    print_r($e);
}
