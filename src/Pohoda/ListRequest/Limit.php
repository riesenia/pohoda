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
use Symfony\Component\OptionsResolver\Options;

class Limit extends Agenda
{
    /** @var string[] */
    protected $_elements = ['idFrom', 'count'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild($this->_data['namespace'] . ':limit', '', $this->_namespace($this->_data['namespace']));

        $this->_addElements($xml, $this->_elements, 'ftr');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined($this->_elements);
        $resolver->setDefault('namespace', function (Options $options) {
            return $options['namespace'];
        });

        // validate / format options
        $resolver->setNormalizer('idFrom', $resolver->getNormalizer('int'));
        $resolver->setNormalizer('count', $resolver->getNormalizer('int'));
    }
}
