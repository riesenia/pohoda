<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\Bank;

use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Document\Summary as DocumentSummary;

class Summary extends DocumentSummary
{
    /** @var string[] */
    protected $_elements = ['roundingDocument', 'roundingVAT', 'homeCurrency', 'foreignCurrency'];

    protected function _configureOptions(OptionsResolver $resolver)
    {
        parent::_configureOptions($resolver);

        // validate / format options
        $resolver->setAllowedValues('roundingDocument', ['none']);
        $resolver->setAllowedValues('roundingVAT', ['none']);
    }
}
