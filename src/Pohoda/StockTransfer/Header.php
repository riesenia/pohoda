<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\StockTransfer;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\AddParameterTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Type\Address;

class Header extends Agenda
{
    use AddParameterTrait;

    /** @var string[] */
    protected $_refElements = ['number', 'store', 'centreSource', 'centreDestination', 'activity', 'contract'];

    /** @var string[] */
    protected $_elements = ['number', 'date', 'time', 'dateOfReceipt', 'timeOfReceipt', 'symPar', 'store', 'text', 'partnerIdentity', 'centreSource', 'centreDestination', 'activity', 'contract', 'note', 'intNote'];

    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process partner identity
        if (isset($data['partnerIdentity'])) {
            $data['partnerIdentity'] = new Address($data['partnerIdentity'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('pre:prevodkaHeader', '', $this->_namespace('pre'));

        $this->_addElements($xml, \array_merge($this->_elements, ['parameters']), 'pre');

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('date', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('time', $resolver->getNormalizer('time'));
        $resolver->setNormalizer('dateOfReceipt', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('timeOfReceipt', $resolver->getNormalizer('time'));
        $resolver->setNormalizer('symPar', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('text', $resolver->getNormalizer('string48'));
    }
}
