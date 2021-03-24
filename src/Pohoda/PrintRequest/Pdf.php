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

class Pdf extends Agenda
{
    /** @var array */
    protected $_elements = ['fileName'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('prn:pdf', '', $this->_namespace('prn'));

        $this->_addElements($xml, $this->_elements, 'prn');

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
        $resolver->setRequired('fileName');
    }
}
