<?php

    return [

        'test_manager1' => [
            'expire' => 60 * 60,
        ],

        'test_manager2' => [
            'connection' => 'mysql',
            'table' => 'tokens',
            'expire' => 60 * 60
        ],
        'test_wrong_connection' => [
            'connection' => 'not_exist',
            'expire' => 60 * 60
        ],
        'test_wrong_table' => [
            'table' => 'not_exist',
            'expire' => 60 * 60
        ]
    ];