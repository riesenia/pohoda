<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Receipt;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\AddParameterTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Type\Address;

class Header extends Agenda
{
    use AddParameterTrait;

    /** @var array */
    protected $_refElements = ['number', 'centre', 'activity', 'contract'];

    /** @var array */
    protected $_elements = ['number', 'date', 'text', 'partnerIdentity', 'symPar', 'centre', 'activity', 'contract', 'note', 'intNote'];

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
        $xml = $this->_createXML()->addChild('pri:prijemkaHeader', null, $this->_namespace('pri'));

        $this->_addElements($xml, \array_merge($this->_elements, ['parameters']), 'pri');

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
        $resolver->setNormalizer('symPar', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('text', $resolver->getNormalizer('string240'));
    }
}
