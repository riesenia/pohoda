<?php
namespace Rshop\Synchronization\Pohoda\Addressbook;

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
    protected $_refElements = ['centre', 'activity', 'contract', 'number'];

    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['identity', 'region', 'phone', 'mobil', 'fax', 'email', 'web', 'ICQ', 'Skype', 'GPS', 'credit', 'priceIDS', 'maturity', 'paymentType', 'agreement', 'number', 'ost1', 'ost2', 'message', 'note', 'intNote', 'centre', 'activity', 'contract'];

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
        $resolver->setNormalizer('region', $resolver->string32Normalizer);
        $resolver->setNormalizer('phone', $resolver->string40Normalizer);
        $resolver->setNormalizer('mobil', $resolver->string24Normalizer);
        $resolver->setNormalizer('fax', $resolver->string24Normalizer);
        $resolver->setNormalizer('email', $resolver->string98Normalizer);
        $resolver->setNormalizer('web', $resolver->string32Normalizer);
        $resolver->setNormalizer('ICQ', $resolver->string12Normalizer);
        $resolver->setNormalizer('Skype', $resolver->string32Normalizer);
        $resolver->setNormalizer('GPS', $resolver->string38Normalizer);
        $resolver->setNormalizer('credit', $resolver->floatNormalizer);
        $resolver->setNormalizer('priceIDS', $resolver->string10Normalizer);
        $resolver->setNormalizer('maturity', $resolver->intNormalizer);
        $resolver->setAllowedValues('paymentType', ['draft', 'cash', 'postal', 'delivery', 'creditcard', 'advance', 'encashment', 'cheque', 'compensation']);
        $resolver->setNormalizer('agreement', $resolver->string12Normalizer);
        $resolver->setNormalizer('ost1', $resolver->string8Normalizer);
        $resolver->setNormalizer('ost2', $resolver->string8Normalizer);
        $resolver->setNormalizer('message', $resolver->string64Normalizer);
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
        // process identity
        if (isset($data['identity'])) {
            $data['identity'] = new Address($data['identity'], $ico, $resolveOptions);
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
        $xml = $this->_createXML()->addChild('adb:addressbookHeader', null, $this->_namespace('adb'));

        $this->_addElements($xml, array_merge($this->_elements, ['parameters']), 'adb');

        return $xml;
    }
}
