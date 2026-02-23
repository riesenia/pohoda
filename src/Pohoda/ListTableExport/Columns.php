<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\ListTableExport;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class Columns extends Agenda
{
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('tbe:columns', '', $this->_namespace('tbe'));

        // add each column
        foreach ($this->_data['columns'] as $column) {
            $xml->addChild('tbe:column', $this->_sanitize($column), $this->_namespace('tbe'));
        }

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['columns']);

        // validate / format options
        $resolver->setRequired('columns');
        $resolver->setAllowedTypes('columns', 'array');
    }
}
