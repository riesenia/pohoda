<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\CashSlip;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\AddParameterTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Type\Address;

class Header extends Agenda
{
    use AddParameterTrait;

    /** @var string[] */
    protected $_refElements = ['number', 'accounting', 'paymentType', 'priceLevel', 'centre', 'activity', 'contract', 'kasa'];

    /** @var string[] */
    protected $_elements = ['prodejkaType', 'number', 'date', 'accounting', 'text', 'partnerIdentity', 'paymentType', 'priceLevel', 'centre', 'activity', 'contract', 'kasa', 'note', 'intNote'];

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
        $xml = $this->_createXML()->addChild('pro:prodejkaHeader', '', $this->_namespace('pro'));

        $this->_addElements($xml, \array_merge($this->_elements, ['parameters']), 'pro');

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
        $resolver->setDefault('prodejkaType', 'saleVoucher');
        $resolver->setAllowedValues('prodejkaType', ['saleVoucher', 'deposit', 'withdrawal']);
        $resolver->setNormalizer('date', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('text', $resolver->getNormalizer('string240'));
    }
}
