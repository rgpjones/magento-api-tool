<?php
require_once __DIR__ . '/../RemoteService.php';

try {
    // Get a sales order information by Magento increment ID
    $file = new stdClass;
    $file->content = base64_encode(file_get_contents($argv[2]));
    $file->mime = 'image/jpeg';
    $file->name = 'Kitten';

    $media = new stdClass;
    $media->file = $file;
    $media->label = 'Kitten';
    $media->position = 1;
    $media->types = array('small_image', 'image', 'thumbnail');
    $media->storeView = 'default';

    $service = new RemoteService();
    $result = $service->catalogProductAttributeMediaCreate(
        array(
            'product' => $argv[1],
            'data' => $media
        )
    );
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
