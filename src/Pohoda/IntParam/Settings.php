<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\IntParam;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class Settings extends Agenda
{
    /** @var array */
    protected $_refElements = ['currency'];

    /** @var array */
    protected $_elements = ['unit', 'length', 'currency', 'parameterList'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('ipm:parameterSettings', '', $this->_namespace('ipm'));

        $this->_addElements($xml, $this->_elements, 'ipm');

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
        $resolver->setNormalizer('length', $resolver->getNormalizer('int'));
    }
}
