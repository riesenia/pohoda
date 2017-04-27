<?php
namespace Rshop\Synchronization\Pohoda\IssueSlip;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Common\AddParameterTrait;
use Rshop\Synchronization\Pohoda\Type\Address;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Header extends Agenda
{
    use AddParameterTrait;

    /**
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = ['number', 'priceLevel', 'paymentType', 'centre', 'activity', 'contract', 'carrier', 'regVATinEU'];

    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['number', 'date', 'numberOrder', 'dateOrder', 'text', 'partnerIdentity', 'acc', 'symPar', 'priceLevel', 'paymentType', 'isExecuted', 'isDelivered', 'centre', 'activity', 'contract', 'carrier', 'regVATinEU', 'note', 'intNote'];

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
        $resolver->setNormalizer('date', $resolver->dateNormalizer);
        $resolver->setNormalizer('numberOrder', $resolver->string32Normalizer);
        $resolver->setNormalizer('dateOrder', $resolver->dateNormalizer);
        $resolver->setNormalizer('text', $resolver->string240Normalizer);
        $resolver->setNormalizer('acc', $resolver->string9Normalizer);
        $resolver->setNormalizer('symPar', $resolver->string20Normalizer);
        $resolver->setNormalizer('isExecuted', $resolver->boolNormalizer);
        $resolver->setNormalizer('isDelivered', $resolver->boolNormalizer);
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

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('vyd:vydejkaHeader', null, $this->_namespace('vyd'));

        $this->_addElements($xml, array_merge($this->_elements, ['parameters']), 'vyd');

        return $xml;
    }
}
