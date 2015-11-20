<?php
namespace Rshop\Synchronization\Pohoda\Order;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Type\CurrencyItem;
use Rshop\Synchronization\Pohoda\Type\StockItem;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Item extends Agenda
{
    /**
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = ['centre', 'activity', 'contract'];

    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['text', 'quantity', 'delivered', 'unit', 'coefficient', 'payVAT', 'rateVAT', 'discountPercentage', 'homeCurrency', 'foreignCurrency', 'note', 'code', 'stockItem', 'centre', 'activity', 'contract'];

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
        $resolver->setNormalizer('text', $resolver->string90Normalizer);
        $resolver->setNormalizer('quantity', $resolver->floatNormalizer);
        $resolver->setNormalizer('delivered', $resolver->floatNormalizer);
        $resolver->setNormalizer('unit', $resolver->string10Normalizer);
        $resolver->setNormalizer('coefficient', $resolver->floatNormalizer);
        $resolver->setNormalizer('payVAT', $resolver->boolNormalizer);
        $resolver->setAllowedValues('rateVAT', ['none', 'low', 'high']);
        $resolver->setNormalizer('discountPercentage', $resolver->floatNormalizer);
        $resolver->setNormalizer('note', $resolver->string90Normalizer);
        $resolver->setNormalizer('code', $resolver->string64Normalizer);
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
            $data['homeCurrency'] = new CurrencyItem($data['homeCurrency'], $ico, $resolveOptions);
        }
        // process foreign currency
        if (isset($data['foreignCurrency'])) {
            $data['foreignCurrency'] = new CurrencyItem($data['foreignCurrency'], $ico, $resolveOptions);
        }
        // process stock item
        if (isset($data['stockItem'])) {
            $data['stockItem'] = new StockItem($data['stockItem'], $ico, $resolveOptions);
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
        $xml = $this->_createXML()->addChild('ord:orderItem', null, $this->_namespace('ord'));

        $this->_addElements($xml, $this->_elements, 'ord');

        return $xml;
    }
}
