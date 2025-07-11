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

class Price extends Agenda
{
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('stk:stockPriceItem', '', $this->_namespace('stk'));

        return $this->_addRefElement($xml, 'stk:stockPrice', $this->_data);
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['ids', 'price']);
    }
}
