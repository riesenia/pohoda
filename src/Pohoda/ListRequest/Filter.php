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
    /** @var array */
    protected $_refElements = ['storage', 'store'];

    /** @var array */
    protected $_elements = ['id', 'code', 'EAN', 'name', 'storage', 'store', 'internet', 'company', 'ico', 'dic', 'lastChanges'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('ftr:filter', null, $this->_namespace('ftr'));

        $this->_addElements($xml, ['id', 'lastChanges'], 'ftr');

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
        $resolver->setNormalizer('id', $resolver->getNormalizer('int'));
        $resolver->setNormalizer('internet', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('lastChanges', $resolver->getNormalizer('datetime'));
    }
}
