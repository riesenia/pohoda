<?php
namespace Rshop\Synchronization\Pohoda;

use Rshop\Synchronization\Pohoda\Common\AddActionTypeTrait;
use Rshop\Synchronization\Pohoda\Common\AddParameterToHeaderTrait;
use Rshop\Synchronization\Pohoda\Addressbook\Header;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Addressbook extends Agenda
{
    use AddActionTypeTrait, AddParameterToHeaderTrait;

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
        $data = ['header' => new Header($data, $ico, $resolveOptions)];

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('adb:addressbook', null, $this->_namespace('adb'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['actionType', 'header'], 'adb');

        // parameters
        #$this->_addParameters($header, 'adb');

        return $xml;
    }
}
