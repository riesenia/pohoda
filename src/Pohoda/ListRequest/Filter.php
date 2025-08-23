<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\ListRequest;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class Filter extends Agenda
{
    /** @var string[] */
    protected $_refElements = ['extId', 'storage', 'store', 'selectedNumbers', 'selectedCompanys', 'selectedIco'];

    /** @var string[] */
    protected $_elements = ['id', 'extId', 'code', 'EAN', 'name', 'storage', 'store', 'internet', 'company', 'ico', 'dic', 'lastChanges', 'dateFrom', 'dateTill', 'selectedNumbers', 'selectedCompanys', 'selectedIco'];

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
        $resolver->setNormalizer('internet', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('lastChanges', $resolver->getNormalizer('datetime'));
        $resolver->setNormalizer('dateFrom', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateTill', $resolver->getNormalizer('date'));
    }
}
