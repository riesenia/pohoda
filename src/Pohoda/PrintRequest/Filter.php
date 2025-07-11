<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\PrintRequest;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class Filter extends Agenda
{
    /** @var string[] */
    protected $_elements = ['id'];

    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('ftr:filter', '', $this->_namespace('ftr'));

        $this->_addElements($xml, $this->_elements, 'ftr');

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('id', $resolver->getNormalizer('int'));
    }
}
