<?php
namespace Rshop\Synchronization\Pohoda;

use Rshop\Synchronization\Pohoda\Type\Address;
use Rshop\Synchronization\Pohoda\Type\ShipToAddress;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Addressbook extends Agenda
{
    /**
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = ['centre', 'activity', 'contract', 'number'];

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['identity', 'region', 'phone', 'mobil', 'fax', 'email', 'web', 'ICQ', 'Skype', 'GPS', 'credit', 'priceIDS', 'maturity', 'paymentType', 'agreement', 'number', 'ost1', 'ost2', 'message', 'note', 'intNote', 'centre', 'activity', 'contract']);

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
        parent::__construct($data, $ico, $resolveOptions);

        // process identity
        if (isset($this->_data['identity'])) {
            if (isset($this->_data['identity']['address'])) {
                $this->_data['identity']['address'] = new Address($this->_data['identity']['address'], $ico, $resolveOptions);
            }
            if (isset($this->_data['identity']['shipToAddress'])) {
                $this->_data['identity']['shipToAddress'] = new ShipToAddress($this->_data['identity']['shipToAddress'], $ico, $resolveOptions);
            }
        }
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

        // action type
        $this->_addActionType($xml, 'adb');

        $header = $xml->addChild('adb:addressbookHeader');

        $this->_addElements($header, ['identity', 'region', 'phone', 'mobil', 'fax', 'email', 'web', 'ICQ', 'Skype', 'GPS', 'credit', 'priceIDS', 'maturity', 'paymentType', 'agreement', 'number', 'ost1', 'ost2', 'message', 'note', 'intNote', 'centre', 'activity', 'contract'], 'adb');

        // parameters
        $this->_addParameters($header, 'adb');

        return $xml;
    }
}
