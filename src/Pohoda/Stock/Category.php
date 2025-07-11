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

class Category extends Agenda
{
    public function getXML(): \SimpleXMLElement
    {
        return $this->_createXML()->addChild('stk:idCategory', $this->_data['idCategory'], $this->_namespace('stk'));
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['idCategory']);

        // validate / format options
        $resolver->setRequired('idCategory');
        $resolver->setNormalizer('idCategory', $resolver->getNormalizer('int'));
    }
}
