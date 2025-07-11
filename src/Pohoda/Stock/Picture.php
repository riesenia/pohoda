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

class Picture extends Agenda
{
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('stk:picture', '', $this->_namespace('stk'));
        $xml->addAttribute('default', $this->_data['default']);

        $this->_addElements($xml, ['filepath', 'description', 'order'], 'stk');

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['filepath', 'description', 'order', 'default']);

        // validate / format options
        $resolver->setRequired('filepath');
        $resolver->setNormalizer('order', $resolver->getNormalizer('int'));
        $resolver->setDefault('default', 'false');
        $resolver->setNormalizer('default', $resolver->getNormalizer('bool'));
    }
}
