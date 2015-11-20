<?php
namespace Rshop\Synchronization\Pohoda\Order;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Type\CurrencyHome;
use Rshop\Synchronization\Pohoda\Type\CurrencyForeign;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Summary extends Agenda
{
    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['roundingDocument', 'roundingVAT', 'homeCurrency', 'foreignCurrency'];

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
        $resolver->setAllowedValues('roundingDocument', ['none', 'math2one', 'math2half', 'math2tenth', 'up2one', 'up2half', 'up2tenth', 'down2one', 'down2half', 'down2tenth']);
        $resolver->setAllowedValues('roundingVAT', ['none', 'noneEveryRate', 'up2tenthEveryItem', 'up2tenthEveryRate', 'math2tenthEveryItem', 'math2tenthEveryRate', 'math2halfEveryItem', 'math2halfEveryRate', 'math2intEveryItem', 'math2intEveryRate']);
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
        // process home currency
        if (isset($data['homeCurrency'])) {
            $data['homeCurrency'] = new CurrencyHome($data['homeCurrency'], $ico, $resolveOptions);
        }
        // process foreign currency
        if (isset($data['foreignCurrency'])) {
            $data['foreignCurrency'] = new CurrencyForeign($data['foreignCurrency'], $ico, $resolveOptions);
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
        $xml = $this->_createXML()->addChild('ord:orderSummary', null, $this->_namespace('ord'));

        $this->_addElements($xml, $this->_elements, 'ord');

        return $xml;
    }
}
