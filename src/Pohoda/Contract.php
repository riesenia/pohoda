<?php
namespace Rshop\Synchronization\Pohoda;

use Rshop\Synchronization\Pohoda\Common\AddParameterToHeaderTrait;
use Rshop\Synchronization\Pohoda\Contract\Desc;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Contract extends Agenda
{
    use AddParameterToHeaderTrait;

    /**
     * Root for import
     *
     * @var string
     */
    public static $importRoot = 'lCon:contract';

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['header']);
    }

    /**
     * Construct agenda using provided data
     *
     * @param array data
     * @param string ICO
     * @param bool if options resolver should be used
     */
    public function __construct($data, $ico, $resolveOptions = true)
    {
        // pass to header
        $data = ['header' => new Desc($data, $ico, $resolveOptions)];

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('con:contract', null, $this->_namespace('con'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['header'], 'con');

        return $xml;
    }
}
