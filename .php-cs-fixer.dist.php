<?php
$header = 'This file is part of riesenia/pohoda package.

Licensed under the MIT License
(c) RIESENIA.com';

$config = new Rshop\CS\Config\Rshop($header);

$config->setStrict()
    ->setRule('general_phpdoc_annotation_remove', ['annotations' => ['author']])
    ->getFinder()
    ->in(__DIR__)
    ->exclude('vendor');

return $config;
