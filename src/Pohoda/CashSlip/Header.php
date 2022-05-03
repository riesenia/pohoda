<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\CashSlip;

use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Document\Header as DocumentHeader;

class Header extends DocumentHeader
{
    /** @var string[] */
    protected $_refElements = ['number', 'accounting', 'paymentType', 'priceLevel', 'centre', 'activity', 'contract', 'kasa'];

    /** @var string[] */
    protected $_elements = ['prodejkaType', 'number', 'date', 'accounting', 'text', 'partnerIdentity', 'paymentType', 'priceLevel', 'centre', 'activity', 'contract', 'kasa', 'note', 'intNote'];

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        parent::_configureOptions($resolver);

        // validate / format options
        $resolver->setDefault('prodejkaType', 'saleVoucher');
        $resolver->setAllowedValues('prodejkaType', ['saleVoucher', 'deposit', 'withdrawal']);
        $resolver->setNormalizer('date', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('text', $resolver->getNormalizer('string240'));
    }
}
