<?php
require_once 'RemoteService.php';

try {
    $service = new RemoteService();
    $randomId = mt_rand(1, 1000000);
    $sku = 'TEST' . $randomId;

    $newProductData = array(
        'name'                   => 'Product Name ' . $randomId,
        'description'            => 'Product description',
        'short_description'      => 'Product short description',
        'websites'               => array('base'), // Websites the product should show up on
        'price'                  => 9.99,
        'category_ids'           => '',
        'visibility'             => 4,
        'status'                 => 1,
        'weight'                 => 1,
        'tax_class_id'           => 2,
    );

    $productId = $service->catalogProductCreate(array(
        'type' => 'simple',
        'set'  => 4, // attribute set ID
        'sku'  => $sku,
        'productData' => $newProductData
    ));

    echo "Product ID: " . $productId . "\n";

} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) . "':\n";
    print_r($e);
}
