<?php

return [
    "premium_product" => [
        "subscribe_private_classroom"=> [
            "id" => env('STORE_SUB_PRIVATE_PRODUCT_ID'),
            "name" => env('STORE_SUB_PRIVATE_PRODUCT_NAME'),
            "price" => env('STORE_SUB_PRIVATE_PRODUCT_PRICE'),
            "duration" => env('STORE_SUB_PRIVATE_PRODUCT_VALIDITY')
        ]
    ]
];
