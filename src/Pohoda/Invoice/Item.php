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
use Riesenia\Pohoda\Document\Item as DocumentItem;

class Item extends DocumentItem
{
    /** @var string[] */
    protected $_refElements = ['typeServiceMOSS', 'accounting', 'classificationVAT', 'classificationKVDPH', 'centre', 'activity', 'contract'];

    /** @var string[] */
    protected $_elements = ['text', 'quantity', 'unit', 'coefficient', 'payVAT', 'rateVAT', 'percentVAT', 'discountPercentage', 'homeCurrency', 'foreignCurrency', 'typeServiceMOSS', 'note', 'code', 'guarantee', 'guaranteeType', 'stockItem', 'accounting', 'classificationVAT', 'classificationKVDPH', 'centre', 'activity', 'contract', 'expirationDate', 'PDP'];

    protected function _configureOptions(OptionsResolver $resolver)
    {
        parent::_configureOptions($resolver);

        // validate / format options
        $resolver->setNormalizer('text', $resolver->getNormalizer('string90'));
        $resolver->setNormalizer('quantity', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('unit', $resolver->getNormalizer('string10'));
        $resolver->setNormalizer('coefficient', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('payVAT', $resolver->getNormalizer('bool'));
        $resolver->setAllowedValues('rateVAT', ['none', 'high', 'low', 'third', 'historyHigh', 'historyLow', 'historyThird']);
        $resolver->setNormalizer('percentVAT', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('discountPercentage', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('note', $resolver->getNormalizer('string90'));
        $resolver->setNormalizer('code', $resolver->getNormalizer('string64'));
        $resolver->setNormalizer('guarantee', $resolver->getNormalizer('int'));
        $resolver->setAllowedValues('guaranteeType', ['none', 'hour', 'day', 'month', 'year', 'life']);
        $resolver->setNormalizer('expirationDate', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('PDP', $resolver->getNormalizer('bool'));
    }
}
