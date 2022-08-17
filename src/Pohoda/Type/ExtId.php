<?php

declare(strict_types=1);

namespace Riesenia\Pohoda\Type;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class ExtId extends Agenda
{
    /** @var string[] */
    protected $_elements = ['ids', 'exSystemName', 'exSystemText'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('ord:extId', '', $this->_namespace('ord'));

        $this->_addElements($xml, $this->_elements, 'typ');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        $resolver->setNormalizer('ids', $resolver->getNormalizer('string64'));
        $resolver->setNormalizer('exSystemName', $resolver->getNormalizer('string64'));
        $resolver->setNormalizer('exSystemText', $resolver->getNormalizer('string'));
    }
}
