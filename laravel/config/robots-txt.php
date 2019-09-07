<?php
return [
    'paths' => [
        'production' => [
            '*' => [
                '/admin',
                '/admin/*',
                '/api',
                '/api/*',
                '/export',
                '/export/*',
                '/install',
                '/storage/private',
                '/storage/private/*',
            ]
        ],
        'staging' => [
	        '*' => [
	            '/'
	        ]
	    ]
    ]
];