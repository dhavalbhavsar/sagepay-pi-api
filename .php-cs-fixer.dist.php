<?php

$finder = new PhpCsFixer\Finder();
$finder->in(['src', 'tests']);

$config = new PhpCsFixer\Config();
$config
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short']
    ])
    ->setRiskyAllowed(false)
    ->setFinder($finder)
;

return $config;
