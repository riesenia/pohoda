<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\IssueSlip;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\AddParameterTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Type\Address;

class Header extends Agenda
{
    use AddParameterTrait;

    /** @var array */
    protected $_refElements = ['number', 'priceLevel', 'paymentType', 'centre', 'activity', 'contract', 'carrier', 'regVATinEU'];

    /** @var array */
    protected $_elements = ['number', 'date', 'numberOrder', 'dateOrder', 'text', 'partnerIdentity', 'acc', 'symPar', 'priceLevel', 'paymentType', 'isExecuted', 'isDelivered', 'centre', 'activity', 'contract', 'carrier', 'regVATinEU', 'note', 'intNote'];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process partner identity
        if (isset($data['partnerIdentity'])) {
            $data['partnerIdentity'] = new Address($data['partnerIdentity'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('vyd:vydejkaHeader', null, $this->_namespace('vyd'));

        $this->_addElements($xml, \array_merge($this->_elements, ['parameters']), 'vyd');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('date', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('numberOrder', $resolver->getNormalizer('string32'));
        $resolver->setNormalizer('dateOrder', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('text', $resolver->getNormalizer('string240'));
        $resolver->setNormalizer('acc', $resolver->getNormalizer('string9'));
        $resolver->setNormalizer('symPar', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('isExecuted', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('isDelivered', $resolver->getNormalizer('bool'));
    }
}
