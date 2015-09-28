<?php
namespace Rshop\Synchronization\Pohoda;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Stock extends Agenda
{
    /**
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = ['storage', 'typePrice', 'typeRP'];

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['stockType', 'code', 'EAN', 'PLU', 'isSales', 'isSerialNumber', 'isInternet', 'isBatch', 'purchasingRateVAT', 'sellingRateVAT', 'name', 'nameComplement', 'unit', 'unit2', 'unit3', 'coefficient2', 'coefficient3', 'storage', 'typePrice', 'purchasingPrice', 'sellingPrice', 'limitMin', 'limitMax', 'mass', 'volume', 'supplier', 'shortName', 'typeRP', 'guaranteeType', 'guarantee', 'producer', 'description', 'description2', 'note']);

        // validate / format options
        $resolver->setDefault('stockType', 'card');
        $resolver->setAllowedValues('stockType', ['card', 'text', 'service', 'package', 'set', 'product']);
        $resolver->setNormalizer('isSales', $resolver->boolNormalizer);
        $resolver->setNormalizer('isSerialNumber', $resolver->boolNormalizer);
        $resolver->setNormalizer('isInternet', $resolver->boolNormalizer);
        $resolver->setNormalizer('isBatch', $resolver->boolNormalizer);
        $resolver->setAllowedValues('purchasingRateVAT', ['none', 'low', 'high']);
        $resolver->setAllowedValues('sellingRateVAT', ['none', 'low', 'high']);
        $resolver->setNormalizer('name', $resolver->string90Normalizer);
        $resolver->setNormalizer('nameComplement', $resolver->string90Normalizer);
        $resolver->setNormalizer('unit', $resolver->string10Normalizer);
        $resolver->setNormalizer('unit2', $resolver->string10Normalizer);
        $resolver->setNormalizer('unit3', $resolver->string10Normalizer);
        $resolver->setNormalizer('coefficient2', $resolver->floatNormalizer);
        $resolver->setNormalizer('coefficient3', $resolver->floatNormalizer);
        $resolver->setNormalizer('purchasingPrice', $resolver->floatNormalizer);
        $resolver->setNormalizer('sellingPrice', $resolver->floatNormalizer);
        $resolver->setNormalizer('limitMin', $resolver->floatNormalizer);
        $resolver->setNormalizer('limitMax', $resolver->floatNormalizer);
        $resolver->setNormalizer('mass', $resolver->floatNormalizer);
        $resolver->setNormalizer('volume', $resolver->floatNormalizer);
        $resolver->setNormalizer('shortName', $resolver->string24Normalizer);
        $resolver->setAllowedValues('guaranteeType', ['none', 'hour', 'day', 'month', 'year', 'life']);
        $resolver->setNormalizer('guarantee', $resolver->intNormalizer);
        $resolver->setNormalizer('producer', $resolver->string90Normalizer);
        $resolver->setNormalizer('description', $resolver->string240Normalizer);
    }

    /**
     * Add price
     *
     * @param string price code
     * @param float price
     * @return \Rshop\Synchronization\Pohoda\Stock
     */
    public function addPrice($code, $value)
    {
        if (!isset($this->_data['prices'])) {
            $this->_data['prices'] = [];
        }

        $this->_data['prices'][$code] = (float)$value;

        return $this;
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('stk:stock', null, $this->_namespace('stk'));
        $xml->addAttribute('version', '2.0');

        // action type
        $this->_addActionType($xml, 'stk');

        $header = $xml->addChild('stk:stockHeader');

        $this->_addElements($header, ['stockType', 'code', 'EAN', 'PLU', 'isSales', 'isSerialNumber', 'isInternet', 'isBatch', 'purchasingRateVAT', 'sellingRateVAT', 'name', 'nameComplement', 'unit', 'unit2', 'unit3', 'coefficient2', 'coefficient3', 'storage', 'typePrice', 'purchasingPrice', 'sellingPrice', 'limitMin', 'limitMax', 'mass', 'volume', 'shortName', 'typeRP', 'guaranteeType', 'guarantee', 'producer', 'description', 'description2', 'note'], 'stk');

        // parameters
        $this->_addParameters($header, 'stk');

        // prices
        if (isset($this->_data['prices'])) {
            $prices = $xml->addChild('stk:stockPriceItem');

            foreach ($this->_data['prices'] as $code => $value) {
                $this->_addRefElement($prices, 'stk:stockPrice', ['ids' => $code, 'price' => $value]);
            }
        }

        return $xml;
    }
}
