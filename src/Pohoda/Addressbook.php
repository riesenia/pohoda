<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda\Addressbook\Header;
use Riesenia\Pohoda\Common\AddActionTypeTrait;
use Riesenia\Pohoda\Common\AddParameterToHeaderTrait;
use Riesenia\Pohoda\Common\OptionsResolver;

class Addressbook extends Agenda
{
    use AddActionTypeTrait;
    use AddParameterToHeaderTrait;

    /** @var string */
    public static $importRoot = 'lAdb:addressbook';

    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // pass to header
        if ($data) {
            $data = ['header' => new Header($data, $ico, $resolveOptions)];
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('adb:addressbook', '', $this->_namespace('adb'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['actionType', 'header'], 'adb');

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['header']);
    }
}
