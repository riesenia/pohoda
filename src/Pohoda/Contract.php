<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda\Common\AddParameterToHeaderTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Contract\Desc;

class Contract extends Agenda
{
    use AddParameterToHeaderTrait;

    /** @var string */
    public static $importRoot = 'lCon:contract';

    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // pass to header
        $data = ['header' => new Desc($data, $ico, $resolveOptions)];

        parent::__construct($data, $ico, $resolveOptions);
    }

    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('con:contract', '', $this->_namespace('con'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['header'], 'con');

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['header']);
    }
}
