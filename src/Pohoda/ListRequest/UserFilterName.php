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

class UserFilterName extends Agenda
{
    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        return $this->_createXML()->addChild('ftr:userFilterName', $this->_data['userFilterName'], $this->_namespace('ftr'));
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['userFilterName']);

        // validate / format options
        $resolver->setRequired('userFilterName');
        $resolver->setNormalizer('userFilterName', $resolver->getNormalizer('string100'));
    }
}
