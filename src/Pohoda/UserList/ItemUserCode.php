<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\UserList;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class ItemUserCode extends Agenda
{
    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('lst:itemUserCode', '', $this->_namespace('lst'));
        // TODO: We usually sanitize attributes. Should we do the same here?
        $xml->addAttribute('code', $this->_data['code']);
        $xml->addAttribute('name', $this->_data['name']);

        if (isset($this->_data['constant'])) {
            $xml->addAttribute('constants', $this->_data['constants']);
        }

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['code', 'name', 'constant']);

        // validate / format options
        $resolver->setRequired('code');
        $resolver->setRequired('name');
    }
}
