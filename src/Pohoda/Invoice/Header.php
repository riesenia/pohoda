<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\Invoice;

use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Document\Header as DocumentHeader;

class Header extends DocumentHeader
{
    /** @var string[] */
    protected $_refElements = ['extId', 'number', 'accounting', 'classificationVAT', 'classificationKVDPH', 'order', 'paymentType', 'priceLevel', 'account', 'paymentAccount', 'centre', 'activity', 'contract', 'regVATinEU', 'MOSS', 'evidentiaryResourcesMOSS', 'carrier'];

    /** @var string[] */
    protected $_elements = ['extId', 'invoiceType', 'number', 'symVar', 'originalDocument', 'originalDocumentNumber', 'symPar', 'date', 'dateTax', 'dateAccounting', 'dateKHDPH', 'dateDue', 'dateApplicationVAT', 'dateDelivery', 'accounting', 'classificationVAT', 'classificationKVDPH', 'numberKHDPH', 'text', 'partnerIdentity', 'myIdentity', 'order', 'numberOrder', 'dateOrder', 'paymentType', 'priceLevel', 'account', 'symConst', 'symSpec', 'paymentAccount', 'paymentTerminal', 'centre', 'activity', 'contract', 'regVATinEU', 'MOSS', 'evidentiaryResourcesMOSS', 'accountingPeriodMOSS', 'dateTaxOriginalDocumentMOSS', 'note', 'carrier', 'intNote'];

    protected function _configureOptions(OptionsResolver $resolver)
    {
        parent::_configureOptions($resolver);

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
        $resolver->setNormalizer('dateTaxOriginalDocumentMOSS', $resolver->getNormalizer('date'));
    }
}
