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

class RelatedLink extends Agenda
{
    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('stk:relatedLink', '', $this->_namespace('stk'));

        $this->_addElements($xml, ['addressURL', 'description'], 'stk');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['addressURL', 'description']);

        // validate / format options
        $resolver->setRequired('addressURL');
        $resolver->setNormalizer('addressURL', $resolver->getNormalizer('string255'));
        $resolver->setNormalizer('description', $resolver->getNormalizer('string90'));
    }
}
