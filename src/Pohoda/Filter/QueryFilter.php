<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Filter;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class QueryFilter extends Agenda
{
    /** @var string[] */
    protected $_elements = ['filter', 'textName'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('ftr:queryFilter', '', $this->_namespace('ftr'));

        $this->_addElements($xml, $this->_elements, 'ftr');

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
        $resolver->setRequired('filter');
        $resolver->setNormalizer('filter', $resolver->getNormalizer('string255'));
        $resolver->setNormalizer('textName', $resolver->getNormalizer('string200'));
    }
}
