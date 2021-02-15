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
use Riesenia\Pohoda\Common\SetNodeNameTrait;

class CurrencyHome extends Agenda
{
    use SetNamespaceTrait;
    use SetNodeNameTrait;

    /** @var array */
    protected $_refElements = ['round'];

    /** @var array */
    protected $_elements = ['priceNone', 'price3', 'price3VAT', 'price3Sum', 'priceLow', 'priceLowVAT', 'priceLowSum', 'priceHigh', 'priceHighVAT', 'priceHighSum', 'round'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        if ($this->_namespace === null) {
            throw new \LogicException('Namespace not set.');
        }

        if ($this->_nodeName === null) {
            throw new \LogicException('Node name not set.');
        }

        $xml = $this->_createXML()->addChild($this->_namespace . ':' . $this->_nodeName, '', $this->_namespace($this->_namespace));

        $this->_addElements($xml, $this->_elements, 'typ');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('priceNone', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('price3', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('price3VAT', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('price3Sum', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('priceLow', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('priceLowVAT', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('priceLowSum', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('priceHigh', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('priceHighVAT', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('priceHighSum', $resolver->getNormalizer('float'));
    }
}
