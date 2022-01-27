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

class RecyclingContrib extends Agenda
{
    /** @var string[] */
    protected $_refElements = ['recyclingContribType'];

    /** @var string[] */
    protected $_elements = ['recyclingContribType', 'coefficientOfRecyclingContrib'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('stk:recyclingContrib', '', $this->_namespace('stk'));

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
        $resolver->setNormalizer('coefficientOfRecyclingContrib', $resolver->getNormalizer('float'));
    }
}
