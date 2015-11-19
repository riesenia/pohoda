<?php
namespace Rshop\Synchronization\Pohoda\Type;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Common\SetNamespaceTrait;
use Rshop\Synchronization\Pohoda\Common\SetNodeNameTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Address extends Agenda
{
    use SetNamespaceTrait, SetNodeNameTrait;

    /**
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = ['extId'];

    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['id', 'extId', 'address', 'shipToAddress'];

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('id', $resolver->intNormalizer);
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
        // process address
        if (isset($data['address'])) {
            $data['address'] = new AddressType($data['address'], $ico, $resolveOptions);
        }
        if (isset($data['shipToAddress'])) {
            $data['shipToAddress'] = new ShipToAddressType($data['shipToAddress'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        if (is_null($this->_namespace)) {
            throw new \LogicException("Namespace not set.");
        }

        if (is_null($this->_nodeName)) {
            throw new \LogicException("Node name not set.");
        }

        $xml = $this->_createXML()->addChild($this->_namespace . ':' . $this->_nodeName, null, $this->_namespace($this->_namespace));

        $this->_addElements($xml, $this->_elements, 'typ');

        return $xml;
    }
}
