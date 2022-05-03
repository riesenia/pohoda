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

class CurrencyItem extends Agenda
{
    use SetNamespaceTrait;

    /** @var string[] */
    protected $_elements = ['unitPrice', 'price', 'priceVAT', 'priceSum'];

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('unitPrice', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('price', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('priceVAT', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('priceSum', $resolver->getNormalizer('float'));
    }
}
