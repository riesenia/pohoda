<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\IntDoc;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\AddParameterTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Type\Address;
use Riesenia\Pohoda\Type\MyAddress;

class Header extends Agenda
{
    use AddParameterTrait;

    /** @var string[] */
    protected $_refElements = ['number', 'accounting', 'classificationVAT', 'classificationKVDPH', 'centre', 'activity', 'contract', 'regVATinEU'];

    /** @var string[] */
    protected $_elements = ['number', 'symVar', 'symPar', 'originalDocumentNumber', 'originalCorrectiveDocument', 'date', 'dateTax', 'dateAccounting', 'dateDelivery', 'dateKVDPH', 'dateKHDPH', 'accounting', 'classificationVAT', 'classificationKVDPH', 'numberKHDPH', 'text', 'partnerIdentity', 'myIdentity', 'liquidation', 'centre', 'activity', 'contract', 'regVATinEU', 'note', 'intNote', 'markRecord'];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process partner identity
        if (isset($data['partnerIdentity'])) {
            $data['partnerIdentity'] = new Address($data['partnerIdentity'], $ico, $resolveOptions);
        }

        // process my identity
        if (isset($data['myIdentity'])) {
            $data['myIdentity'] = new MyAddress($data['myIdentity'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('int:intDocHeader', '', $this->_namespace('int'));

        $this->_addElements($xml, \array_merge($this->_elements, ['parameters']), 'int');

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
        $resolver->setNormalizer('symVar', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('symPar', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('date', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateTax', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateAccounting', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateDelivery', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateKVDPH', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateKHDPH', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('numberKHDPH', $resolver->getNormalizer('string32'));
        $resolver->setNormalizer('text', $resolver->getNormalizer('string240'));
        $resolver->setNormalizer('liquidation', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('markRecord', $resolver->getNormalizer('bool'));
    }
}
