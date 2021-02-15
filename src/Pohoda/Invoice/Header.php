<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Invoice;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\AddParameterTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Type\Address;
use Riesenia\Pohoda\Type\MyAddress;

class Header extends Agenda
{
    use AddParameterTrait;

    /** @var array */
    protected $_refElements = ['number', 'accounting', 'classificationVAT', 'classificationKVDPH', 'order', 'paymentType', 'priceLevel', 'account', 'paymentAccount', 'centre', 'activity', 'contract', 'regVATinEU', 'carrier'];

    /** @var array */
    protected $_elements = ['extId', 'invoiceType', 'number', 'symVar', 'originalDocument', 'originalDocumentNumber', 'symPar', 'date', 'dateTax', 'dateAccounting', 'dateKHDPH', 'dateDue', 'dateApplicationVAT', 'dateDelivery', 'accounting', 'classificationVAT', 'classificationKVDPH', 'numberKHDPH', 'text', 'partnerIdentity', 'myIdentity', 'order', 'numberOrder', 'dateOrder', 'paymentType', 'priceLevel', 'account', 'symConst', 'symSpec', 'paymentAccount', 'paymentTerminal', 'centre', 'activity', 'contract', 'regVATinEU', 'note', 'carrier', 'intNote'];

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
        $xml = $this->_createXML()->addChild('inv:invoiceHeader', '', $this->_namespace('inv'));

        $this->_addElements($xml, \array_merge($this->_elements, ['parameters']), 'inv');

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
        $resolver->setDefault('invoiceType', 'issuedInvoice');
        $resolver->setAllowedValues('invoiceType', ['issuedInvoice', 'issuedCreditNotice', 'issuedDebitNote', 'issuedAdvanceInvoice', 'receivable', 'issuedProformaInvoice', 'penalty', 'issuedCorrectiveTax', 'receivedInvoice', 'receivedCreditNotice', 'receivedDebitNote', 'receivedAdvanceInvoice', 'commitment', 'receivedProformaInvoice', 'receivedCorrectiveTax']);
        $resolver->setNormalizer('symVar', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('originalDocument', $resolver->getNormalizer('string32'));
        $resolver->setNormalizer('symPar', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('date', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateTax', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateAccounting', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateKHDPH', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateDue', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateApplicationVAT', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateDelivery', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('numberKHDPH', $resolver->getNormalizer('string32'));
        $resolver->setNormalizer('text', $resolver->getNormalizer('string240'));
        $resolver->setNormalizer('numberOrder', $resolver->getNormalizer('string32'));
        $resolver->setNormalizer('dateOrder', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('symConst', $resolver->getNormalizer('string4'));
        $resolver->setNormalizer('symSpec', $resolver->getNormalizer('string16'));
        $resolver->setNormalizer('paymentTerminal', $resolver->getNormalizer('bool'));
    }
}
