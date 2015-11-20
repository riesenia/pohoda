<?php
namespace Rshop\Synchronization\Pohoda\Order;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Common\AddParameterTrait;
use Rshop\Synchronization\Pohoda\Type\Address;
use Rshop\Synchronization\Pohoda\Type\MyAddress;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Header extends Agenda
{
    use AddParameterTrait;

    /**
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = ['number', 'paymentType', 'priceLevel', 'centre', 'activity', 'contract', 'regVATinEU', 'carrier'];

    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['orderType', 'number', 'numberOrder', 'date', 'dateDelivery', 'dateFrom', 'dateTo', 'text', 'partnerIdentity', 'myIdentity', 'paymentType', 'priceLevel', 'isExecuted', 'isReserved', 'centre', 'activity', 'contract', 'regVATinEU', 'note', 'carrier', 'intNote'];

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
        $resolver->setDefault('orderType', 'receivedOrder');
        $resolver->setAllowedValues('orderType', ['receivedOrder', 'issuedOrder']);
        $resolver->setNormalizer('numberOrder', $resolver->string32Normalizer);
        $resolver->setNormalizer('date', $resolver->dateNormalizer);
        $resolver->setNormalizer('dateDelivery', $resolver->dateNormalizer);
        $resolver->setNormalizer('dateFrom', $resolver->dateNormalizer);
        $resolver->setNormalizer('dateTo', $resolver->dateNormalizer);
        $resolver->setNormalizer('text', $resolver->string240Normalizer);
        $resolver->setNormalizer('isExecuted', $resolver->boolNormalizer);
        $resolver->setNormalizer('isReserved', $resolver->boolNormalizer);
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
        // process partner identity
        if (isset($data['partnerIdentity'])) {
            $data['partnerIdentity'] = new Address($data['partnerIdentity'], $ico, $resolveOptions);
        }

        // process my identity
        if (isset($data['myIdentity'])) {
            $data['myIdentity'] = new MyAddress($data['myIdentity'], $ico, $resolveOptions);
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
        $xml = $this->_createXML()->addChild('ord:orderHeader', null, $this->_namespace('ord'));

        $this->_addElements($xml, array_merge($this->_elements, ['parameters']), 'ord');

        return $xml;
    }
}
