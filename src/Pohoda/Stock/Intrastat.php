<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Stock;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class Intrastat extends Agenda
{
    /** @var string[] */
    protected $_elements = ['goodsCode', 'description', 'statistic', 'unit', 'coefficient', 'country'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('stk:intrastat', '', $this->_namespace('stk'));

        $this->_addElements($xml, $this->_elements, 'stk');

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
        $resolver->setNormalizer('goodsCode', $resolver->getNormalizer('string8'));
        $resolver->setNormalizer('description', $resolver->getNormalizer('string255'));
        $resolver->setNormalizer('statistic', $resolver->getNormalizer('string2'));
        $resolver->setNormalizer('unit', $resolver->getNormalizer('string10'));
        $resolver->setNormalizer('coefficient', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('country', $resolver->getNormalizer('string2'));
    }
}
