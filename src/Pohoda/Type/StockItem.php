<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\Type;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Common\SetNamespaceTrait;

class StockItem extends Agenda
{
    use SetNamespaceTrait;

    /** @var string[] */
    protected $_refElements = ['store', 'stockItem'];

    protected $_elementsAttributesMapper = [
        'insertAttachStock' => ['stockItem', 'insertAttachStock', null],
        'applyUserSettingsFilterOnTheStore' => ['stockItem', 'applyUserSettingsFilterOnTheStore', null]
    ];

    /** @var string[] */
    protected $_elements = ['store', 'stockItem', 'insertAttachStock', 'applyUserSettingsFilterOnTheStore', 'serialNumber'];

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('insertAttachStock', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('applyUserSettingsFilterOnTheStore', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('serialNumber', $resolver->getNormalizer('string40'));
    }
}
