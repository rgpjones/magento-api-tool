<?php
require_once 'RemoteService.php';

try {
    $service = new RemoteService();

    if ($service->opt('l')) {
        print_r($service->getFunctions());
        exit;
    }

    if ($service->opt('c')) {
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . '.apid', "conf = " . $service->opt('c') . "\n");
        exit;
    }

    echo "No call\n";
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) . "':\n";
    print_r($e);
}
