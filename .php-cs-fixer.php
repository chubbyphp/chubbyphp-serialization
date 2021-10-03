<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

/** @var array $config */
$config = require __DIR__ . '/vendor/chubbyphp/chubbyphp-dev-helper/phpcs.php';

unset($config['rules']['final_class']);

$config['rules']['final_public_method_for_abstract_class'] = false;

// drop onces code is >= 8.0
unset($config['rules']['phpdoc_to_return_type']);

return (new PhpCsFixer\Config)
    ->setIndent($config['indent'])
    ->setLineEnding($config['lineEnding'])
    ->setRules($config['rules'])
    ->setRiskyAllowed($config['riskyAllowed'])
    ->setFinder($finder)
;
