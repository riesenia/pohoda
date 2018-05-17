<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Contract;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\AddParameterTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Type\Address;

class Desc extends Agenda
{
    use AddParameterTrait;

    /** @var array */
    protected $_refElements = ['number', 'responsiblePerson'];

    /** @var array */
    protected $_elements = ['number', 'datePlanStart', 'datePlanDelivery', 'dateStart', 'dateDelivery', 'dateWarranty', 'text', 'partnerIdentity', 'responsiblePerson', 'note'];

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
        $xml = $this->_createXML()->addChild('con:contractDesc', null, $this->_namespace('con'));

        $this->_addElements($xml, array_merge($this->_elements, ['parameters']), 'con');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        $resolver->setNormalizer('datePlanStart', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('datePlanDelivery', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateStart', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateDelivery', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateWarranty', $resolver->getNormalizer('date'));
        $resolver->setRequired('text');
        $resolver->setNormalizer('text', $resolver->getNormalizer('string90'));
    }
}
