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

class IntParameter extends Agenda
{
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('stk:intParameter', '', $this->_namespace('stk'));

        $this->_addElements($xml, ['intParameterID', 'intParameterType'], 'stk');

        // value
        $xml->addChild('stk:intParameterValues')->addChild('stk:intParameterValue')->addChild('stk:parameterValue', $this->_data['value']);

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['intParameterID', 'intParameterName', 'intParameterOrder', 'intParameterType', 'value']);

        // validate / format options
        $resolver->setRequired('intParameterID');
        $resolver->setNormalizer('intParameterID', $resolver->getNormalizer('int'));
        $resolver->setRequired('intParameterType');
        $resolver->setAllowedValues('intParameterType', ['textValue', 'currencyValue', 'booleanValue', 'numberValue', 'integerValue', 'datetimeValue', 'unit', 'listValue']);
        $resolver->setRequired('value');
    }
}
