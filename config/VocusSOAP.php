<?php

return [
    'proxy_enable' => env('vocus_proxy_enabled', false),
    'proxy_host' => env('vocus_proxy_host',''),
    'proxy_port' =>env('vocus_proxy_port',''),
    'cert_pass' => env('vocus_cert_pass',''),
    'access_key' => env('vocus_access_key',''),
];
