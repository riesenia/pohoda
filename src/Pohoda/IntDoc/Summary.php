<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\IntDoc;

use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Document\Summary as DocumentSummary;

class Summary extends DocumentSummary
{
    /** @var string[] */
    protected $_elements = ['roundingDocument', 'roundingVAT', 'calculateVAT', 'homeCurrency', 'foreignCurrency'];

    protected function _configureOptions(OptionsResolver $resolver)
    {
        parent::_configureOptions($resolver);

        // validate / format options
        $resolver->setAllowedValues('roundingDocument', ['none', 'math2one', 'math2half', 'math2tenth', 'math5cent', 'up2one', 'up2half', 'up2tenth', 'down2one', 'down2half', 'down2tenth']);
        $resolver->setAllowedValues('roundingVAT', ['none', 'noneEveryRate', 'up2tenthEveryItem', 'up2tenthEveryRate', 'math2tenthEveryItem', 'math2tenthEveryRate', 'math2halfEveryItem', 'math2halfEveryRate', 'math2intEveryItem', 'math2intEveryRate']);
        $resolver->setNormalizer('calculateVAT', $resolver->getNormalizer('bool'));
    }
}
