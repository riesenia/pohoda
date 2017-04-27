<?php
namespace Rshop\Synchronization\Pohoda\StockTransfer;

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
    protected $_refElements = ['number', 'store', 'centreSource', 'centreDestination', 'activity', 'contract'];

    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['number', 'date', 'time', 'dateOfReceipt', 'timeOfReceipt', 'symPar', 'store', 'text', 'partnerIdentity', 'centreSource', 'centreDestination', 'activity', 'contract', 'note', 'intNote'];

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
        $resolver->setNormalizer('time', $resolver->timeNormalizer);
        $resolver->setNormalizer('dateOfReceipt', $resolver->dateNormalizer);
        $resolver->setNormalizer('timeOfReceipt', $resolver->timeNormalizer);
        $resolver->setNormalizer('symPar', $resolver->string20Normalizer);
        $resolver->setNormalizer('text', $resolver->string48Normalizer);
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
        $xml = $this->_createXML()->addChild('pre:prevodkaHeader', null, $this->_namespace('pre'));

        $this->_addElements($xml, array_merge($this->_elements, ['parameters']), 'pre');

        return $xml;
    }
}
