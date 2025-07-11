<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\Bank;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class StatementNumber extends Agenda
{
    /** @var string[] */
    protected $_elements = ['statementNumber', 'numberMovement'];

    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('bnk:statementNumber', '', $this->_namespace('bnk'));

        $this->_addElements($xml, $this->_elements, 'bnk');

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('statementNumber', $resolver->getNormalizer('string10'));
        $resolver->setNormalizer('numberMovement', $resolver->getNormalizer('string6'));
    }
}
