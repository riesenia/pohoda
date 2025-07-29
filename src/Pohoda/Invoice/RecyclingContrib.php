<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\Invoice;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class RecyclingContrib extends Agenda
{
    /** @var string[] */
    protected $_refElements = ['recyclingContribType'];

    /** @var string[] */
    protected $_elements = ['recyclingContribType', 'recyclingContribText', 'recyclingContribAmount', 'recyclingContribUnit', 'coefficientOfRecyclingContrib'];

    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('inv:recyclingContrib', '', $this->_namespace('inv'));

        $this->_addElements($xml, $this->_elements, 'typ');

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        $resolver->setNormalizer('recyclingContribType', $resolver->getNormalizer('string32'));
        $resolver->setNormalizer('recyclingContribText', $resolver->getNormalizer('string90'));
        $resolver->setNormalizer('recyclingContribAmount', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('recyclingContribUnit', $resolver->getNormalizer('string2'));
        $resolver->setNormalizer('coefficientOfRecyclingContrib', $resolver->getNormalizer('float'));
    }
}
