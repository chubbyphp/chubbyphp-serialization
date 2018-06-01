<?php

return [
    "target_php_version" => null,
    'directory_list' => [
        'src',
        'vendor/doctrine/common',
        'vendor/doctrine/inflector',
        'vendor/pimple/pimple',
        'vendor/psr/container',
        'vendor/psr/http-message',
        'vendor/psr/link',
        'vendor/psr/log',
        'vendor/symfony/yaml'
    ],
    "exclude_analysis_directory_list" => [
        'vendor/'
    ],
    'plugins' => [
        'AlwaysReturnPlugin',
        'UnreachableCodePlugin',
        'DollarDollarPlugin',
        'DuplicateArrayKeyPlugin',
        'PregRegexCheckerPlugin',
        'PrintfCheckerPlugin',
    ],
];
