<?php
namespace Rshop\Synchronization\Pohoda;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Stock extends Agenda
{
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
        $resolver->setAllowedValues('stockType', array('card', 'text', 'service', 'package', 'set', 'product'));
        $resolver->setRequired('code');
        $resolver->setNormalizer('isSales', $resolver->boolNormalizer);
        $resolver->setNormalizer('isSerialNumber', $resolver->boolNormalizer);
        $resolver->setNormalizer('isInternet', $resolver->boolNormalizer);
        $resolver->setNormalizer('isBatch', $resolver->boolNormalizer);
        $resolver->setAllowedValues('purchasingRateVAT', array('none', 'low', 'high'));
        $resolver->setAllowedValues('sellingRateVAT', array('none', 'low', 'high'));
        $resolver->setRequired('name');
        $resolver->setNormalizer('name', $resolver->string90Normalizer);
        $resolver->setNormalizer('nameComplement', $resolver->string90Normalizer);
        $resolver->setNormalizer('unit', $resolver->string10Normalizer);
        $resolver->setNormalizer('unit2', $resolver->string10Normalizer);
        $resolver->setNormalizer('unit3', $resolver->string10Normalizer);
        $resolver->setNormalizer('coefficient2', $resolver->floatNormalizer);
        $resolver->setNormalizer('coefficient3', $resolver->floatNormalizer);
        $resolver->setRequired('storage');
        $resolver->setRequired('typePrice');
        $resolver->setNormalizer('purchasingPrice', $resolver->floatNormalizer);
        $resolver->setNormalizer('sellingPrice', $resolver->floatNormalizer);
        $resolver->setNormalizer('limitMin', $resolver->floatNormalizer);
        $resolver->setNormalizer('limitMax', $resolver->floatNormalizer);
        $resolver->setNormalizer('mass', $resolver->floatNormalizer);
        $resolver->setNormalizer('volume', $resolver->floatNormalizer);
        $resolver->setNormalizer('shortName', $resolver->string24Normalizer);
        $resolver->setAllowedValues('guaranteeType', array('none', 'hour', 'day', 'month', 'year', 'life'));
        $resolver->setNormalizer('guarantee', $resolver->intNormalizer);
        $resolver->setNormalizer('producer', $resolver->string90Normalizer);
        $resolver->setNormalizer('description', $resolver->string240Normalizer);
    }

    /**
     * Get XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('stk:stock', null, $this->_namespace('stk'));
        $xml->addAttribute('version', '2.0');

        $header = $xml->addChild('stk:stockHeader');

        $this->_addElements($header, ['stockType', 'code', 'EAN', 'PLU', 'isSales', 'isSerialNumber', 'isInternet', 'isBatch', 'purchasingRateVAT', 'sellingRateVAT', 'name', 'nameComplement', 'unit', 'unit2', 'unit3', 'coefficient2', 'coefficient3', 'purchasingPrice', 'sellingPrice', 'limitMin', 'limitMax', 'mass', 'volume', 'shortName', 'guaranteeType', 'guarantee', 'producer', 'description', 'description2', 'note'], 'stk');
        $this->_addRefElements($header, ['storage', 'typePrice', 'typeRP'], 'stk');
        $this->_addParameters($header, 'stk');

        return $xml->asXML();
    }
}
