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
    use AddParameterToHeaderTrait {
        addParameter as protected _addParameterToHeader;
    }

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

    /**
     * Set user-defined parameter. Header is created on demand, so that records
     * can be updated by user-defined parameters only.
     *
     * @param string     $name  (can be set without preceding VPr / RefVPr)
     * @param mixed      $value
     * @param mixed|null $list
     *
     * @return Agenda
     */
    public function addParameter(string $name, string $type, $value, $list = null)
    {
        if (!isset($this->_data['header'])) {
            $this->_data['header'] = new Header([], $this->_ico);
        }

        return $this->_addParameterToHeader($name, $type, $value, $list);
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
