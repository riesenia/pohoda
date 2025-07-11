<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\IssueSlip;

use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Document\Header as DocumentHeader;

class Header extends DocumentHeader
{
    /** @var string[] */
    protected $_refElements = ['number', 'priceLevel', 'paymentType', 'centre', 'activity', 'contract', 'carrier', 'regVATinEU', 'MOSS', 'evidentiaryResourcesMOSS'];

    /** @var string[] */
    protected $_elements = ['number', 'date', 'numberOrder', 'dateOrder', 'text', 'partnerIdentity', 'acc', 'symPar', 'priceLevel', 'paymentType', 'isExecuted', 'isDelivered', 'centre', 'activity', 'contract', 'carrier', 'regVATinEU', 'MOSS', 'evidentiaryResourcesMOSS', 'accountingPeriodMOSS', 'note', 'intNote'];

    protected function _configureOptions(OptionsResolver $resolver)
    {
        parent::_configureOptions($resolver);

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
