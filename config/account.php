<?php

return [
    "premium_product" => [
        "subscribe_private_classroom"=> [
            "id" => env('STORE_SUB_PRIVATE_PRODUCT_ID'),
            "type" => env('STORE_SUB_PRIVATE_PRODUCT_TYPE'),
            "name" => env('STORE_SUB_PRIVATE_PRODUCT_NAME'),
            "price" => env('STORE_SUB_PRIVATE_PRODUCT_PRICE'),
            "duration" => env('STORE_SUB_PRIVATE_PRODUCT_VALIDITY')
        ],
        "new_subscribe_private_classroom"=> [
            "id" => env('STORE_SUB_PRIVATE_PRODUCT_ID'),
            "type" => env('STORE_SUB_PRIVATE_PRODUCT_TYPE'),
            "name" => env('STORE_SUB_PRIVATE_PRODUCT_NAME'),
            "price" => env('STORE_SUB_PRIVATE_PRODUCT_PRICE'),
            "duration" => env('STORE_SUB_PRIVATE_PRODUCT_VALIDITY')
        ],
        "private_account"=> [
            "id" => env('STORE_SUB_PRIVATE_PRODUCT_ID'),
            "type" => env('STORE_SUB_PRIVATE_PRODUCT_TYPE'),
            "name" => env('STORE_SUB_PRIVATE_PRODUCT_NAME'),
            "price" => env('STORE_SUB_PRIVATE_PRODUCT_PRICE'),
            "duration" => env('STORE_SUB_PRIVATE_PRODUCT_VALIDITY')
        ],
        "master_account"=> [
            "id" => env('STORE_SUB_MASTER_PRODUCT_ID'),
            "type" => env('STORE_SUB_MASTER_PRODUCT_TYPE'),
            "name" => env('STORE_SUB_MASTER_PRODUCT_NAME'),
            "price" => env('STORE_SUB_MASTER_PRODUCT_PRICE'),
            "duration" => env('STORE_SUB_MASTER_PRODUCT_VALIDITY')
        ]
    ],
    "feelings_options" => [
        [
            "id"    => "851001f3-40f7-41db-a7a2-e1067487e9f8",
            "name"  => "Happy", 
            "file_full_path" => "https://storage.googleapis.com/staging_lets_flip/scorecampus.com/production/assets/images/emots/animations/image/535bdb0e-2ae5-477c-8947-1b3f305f09f6.gif"
        ],
        [
            "id"    => "2c859063-63f9-4d66-a04d-625383c01c72",
            "name"  => "Sad",
            "file_full_path" => "https://storage.googleapis.com/staging_lets_flip/scorecampus.com/production/assets/images/emots/animations/image/1a3cc320-b6fb-4cad-8305-7c80acad17cf.gif"
        ],
        [
            "id"    => "ff9b60b7-59cc-4165-87a7-3d2d1f70cc9e",
            "name"  => "Excited",
            "file_full_path" => "https://storage.googleapis.com/staging_lets_flip/scorecampus.com/production/assets/images/emots/animations/image/21486565-ac9d-4050-92d5-4969dd8b0d78.gif" 
        ],
        [
            "id"    => "e5fc566e-b050-41ef-98df-9ebfb9327d45",
            "name"  => "Tired",
            "file_full_path" => "https://storage.googleapis.com/staging_lets_flip/scorecampus.com/production/assets/images/emots/animations/image/cfee456a-57de-48cc-8cfc-64866e6b7470.gif"
        ]
    ]
];
