<?php
namespace Rshop\Synchronization\Pohoda\Invoice;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Common\AddParameterTrait;
use Rshop\Synchronization\Pohoda\Type\Address;
use Rshop\Synchronization\Pohoda\Type\MyAddress;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Header extends Agenda
{
    use AddParameterTrait;

    /**
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = ['number', 'accounting', 'classificationVAT', 'classificationKVDPH', 'order', 'paymentType', 'priceLevel', 'account', 'paymentAccount', 'centre', 'activity', 'contract', 'regVATinEU', 'carrier'];

    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['extId', 'invoiceType', 'number', 'symVar', 'originalDocument', 'originalDocumentNumber', 'symPar', 'date', 'dateTax', 'dateAccounting', 'dateKHDPH', 'dateDue', 'dateApplicationVAT', 'dateDelivery', 'accounting', 'classificationVAT', 'classificationKVDPH', 'numberKHDPH', 'text', 'partnerIdentity', 'myIdentity', 'order', 'numberOrder', 'dateOrder', 'paymentType', 'priceLevel', 'account', 'symConst', 'symSpec', 'paymentAccount', 'paymentTerminal', 'centre', 'activity', 'contract', 'regVATinEU', 'note', 'carrier', 'intNote'];

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setDefault('invoiceType', 'issuedInvoice');
        $resolver->setAllowedValues('invoiceType', ['issuedInvoice', 'issuedCreditNotice', 'issuedDebitNote', 'issuedAdvanceInvoice', 'receivable', 'issuedProformaInvoice', 'penalty', 'issuedCorrectiveTax', 'receivedInvoice', 'receivedCreditNotice', 'receivedDebitNote', 'receivedAdvanceInvoice', 'commitment', 'receivedProformaInvoice', 'receivedCorrectiveTax']);
        $resolver->setNormalizer('symVar', $resolver->string20Normalizer);
        $resolver->setNormalizer('originalDocument', $resolver->string32Normalizer);
        $resolver->setNormalizer('symPar', $resolver->string20Normalizer);
        $resolver->setNormalizer('date', $resolver->dateNormalizer);
        $resolver->setNormalizer('dateTax', $resolver->dateNormalizer);
        $resolver->setNormalizer('dateAccounting', $resolver->dateNormalizer);
        $resolver->setNormalizer('dateKHDPH', $resolver->dateNormalizer);
        $resolver->setNormalizer('dateDue', $resolver->dateNormalizer);
        $resolver->setNormalizer('dateApplicationVAT', $resolver->dateNormalizer);
        $resolver->setNormalizer('dateDelivery', $resolver->dateNormalizer);
        $resolver->setNormalizer('numberKHDPH', $resolver->string32Normalizer);
        $resolver->setNormalizer('text', $resolver->string240Normalizer);
        $resolver->setNormalizer('numberOrder', $resolver->string32Normalizer);
        $resolver->setNormalizer('dateOrder', $resolver->dateNormalizer);
        $resolver->setNormalizer('symConst', $resolver->string4Normalizer);
        $resolver->setNormalizer('symSpec', $resolver->string16Normalizer);
        $resolver->setNormalizer('paymentTerminal', $resolver->boolNormalizer);
    }

    /**
     * Construct agenda using provided data
     *
     * @param array data
     * @param string ICO
     * @param bool if options resolver should be used
     */
    public function __construct($data, $ico, $resolveOptions = true)
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
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('inv:invoiceHeader', null, $this->_namespace('inv'));

        $this->_addElements($xml, array_merge($this->_elements, ['parameters']), 'inv');

        return $xml;
    }
}
