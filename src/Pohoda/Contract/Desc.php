<?php
namespace Rshop\Synchronization\Pohoda\Contract;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Common\AddParameterTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Desc extends Agenda
{
    use AddParameterTrait;

    /**
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = ['number', 'responsiblePerson'];

    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['number', 'datePlanStart', 'datePlanDelivery', 'dateStart', 'dateDelivery', 'dateWarranty', 'text', 'partnerIdentity', 'responsiblePerson', 'note'];

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        $resolver->setNormalizer('datePlanStart', $resolver->dateNormalizer);
        $resolver->setNormalizer('datePlanDelivery', $resolver->dateNormalizer);
        $resolver->setNormalizer('dateStart', $resolver->dateNormalizer);
        $resolver->setNormalizer('dateDelivery', $resolver->dateNormalizer);
        $resolver->setNormalizer('dateWarranty', $resolver->dateNormalizer);
        $resolver->setRequired('text');
        $resolver->setNormalizer('text', $resolver->string90Normalizer);

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
        $xml = $this->_createXML()->addChild('con:contractDesc', null, $this->_namespace('con'));

        $this->_addElements($xml, array_merge($this->_elements, ['parameters']), 'con');

        return $xml;
    }
}
