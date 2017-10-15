<?php

    return [
        /**
         * Global DB connection to use
         */

        'connection' => null,

        /**
         * Global DB table to use
         */

        'table'      => 'tokens',

        /**
         * Managers list
         */

        'managers'   => [

            /**
             * One manager
             */

            'default' => [

                /**
                 * Connection to use, if set will ignore global DB connection
                 */

                //"connection" => null,

                /**
                 *  Table to use, if set will ignore global DB table
                 */

                //"table" => "tokens",

                /**
                 * Expire in seconds
                 */

                'expire' => 60 * 60
            ]
        ]
    ];
