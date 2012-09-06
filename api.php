<?php
require_once 'RemoteService.php';

try {
    $service = new RemoteService();

    if ($service->opt('l')) {
        $funcs = $service->getFunctions();
        foreach ($funcs as $func) {
            echo "{$func['function']}(" . implode(',', $func['args']) . ")\n";
        }
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
