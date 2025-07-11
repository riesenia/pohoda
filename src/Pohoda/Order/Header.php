<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\Order;

use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Document\Header as DocumentHeader;

class Header extends DocumentHeader
{
    /** @var string[] */
    protected $_refElements = ['extId', 'number', 'paymentType', 'priceLevel', 'centre', 'activity', 'contract', 'regVATinEU', 'MOSS', 'evidentiaryResourcesMOSS', 'carrier'];

    /** @var string[] */
    protected $_elements = ['extId', 'orderType', 'number', 'numberOrder', 'date', 'dateDelivery', 'dateFrom', 'dateTo', 'text', 'partnerIdentity', 'myIdentity', 'paymentType', 'priceLevel', 'isExecuted', 'isReserved', 'centre', 'activity', 'contract', 'regVATinEU', 'MOSS', 'evidentiaryResourcesMOSS', 'accountingPeriodMOSS', 'note', 'carrier', 'intNote', 'markRecord'];

    protected function _configureOptions(OptionsResolver $resolver)
    {
        parent::_configureOptions($resolver);

        // validate / format options
        $resolver->setDefault('orderType', 'receivedOrder');
        $resolver->setAllowedValues('orderType', ['receivedOrder', 'issuedOrder']);
        $resolver->setNormalizer('numberOrder', $resolver->getNormalizer('string32'));
        $resolver->setNormalizer('date', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateDelivery', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateFrom', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateTo', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('text', $resolver->getNormalizer('string240'));
        $resolver->setNormalizer('isExecuted', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('isReserved', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('markRecord', $resolver->getNormalizer('bool'));
    }
}
