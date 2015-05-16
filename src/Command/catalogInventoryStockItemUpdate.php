<?php
require_once __DIR__ . '/../RemoteService.php';

if (!isset($argv[1])) {
    die('Require products IDs as comma separated list');
}

$productIds = explode(',', $argv[1]);

try {

    $service = new RemoteService();

    $baseData = [
        'manage_stock' => 1,
        'use_config_manage_stock' => 0,
        'min_qty' => 1,
        'use_config_min_qty' => 0,
        'min_sale_qty' => 1,
        'use_config_min_sale_qty' => 0,
        'max_sale_qty' => 0,
        'use_config_max_sale_qty' => 0,
        'is_qty_decimal' => 0,
        'backorders' => 0,
        'use_config_backorders' => 0,
        'notify_stock_qty' => 0,
        'use_config_notify_stock_qty' => 0
    ];

    foreach ($productIds as $productId) {

        $result = $service->catalogInventoryStockItemList(['productIds' => [$productId]]);
        $currentQty = $result->result->complexObjectArray->qty;
        $data = [
            'qty' => mt_rand(0, 1) == 1 ? mt_rand(0, 1000) : $currentQty,
        ];
        $data['is_in_stock'] = (int) ($data['qty'] == 0);

        $result = $service->catalogProductInfo(
            ['productId' => $productId]
        );

        $sku = $result->result->sku;

        $service->salesOrderStockQuantityList(
            [
                'orderStatuses' => ['pending', 'processing', 'to_collect'],
                'skus' => [$sku]
            ]
        );

        $result = $service->catalogInventoryStockItemUpdate(
            [
                'productId' => $productId,
                'data' => array_merge($baseData, $data)
            ]
        );
    }
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
