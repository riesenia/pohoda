<?php
namespace Rshop\Synchronization\Pohoda\Stock;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Common\AddParameterTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Header extends Agenda
{
    use AddParameterTrait;

    /**
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = ['storage', 'typePrice', 'typeRP'];

    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['stockType', 'code', 'EAN', 'PLU', 'isSales', 'isSerialNumber', 'isInternet', 'isBatch', 'purchasingRateVAT', 'sellingRateVAT', 'name', 'nameComplement', 'unit', 'unit2', 'unit3', 'coefficient2', 'coefficient3', 'storage', 'typePrice', 'purchasingPrice', 'sellingPrice', 'limitMin', 'limitMax', 'mass', 'volume', 'supplier', 'shortName', 'typeRP', 'guaranteeType', 'guarantee', 'producer', 'description', 'description2', 'note'];

    /**
     * Images counter
     *
     * @var int
     */
    protected $_imagesCounter = 0;

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
     * Add image
     *
     * @param string filepath
     * @param string description
     * @param int order
     * @param bool is default image
     * @return void
     */
    public function addImage($filepath, $description, $order, $default)
    {
        if (!isset($this->_data['pictures'])) {
            $this->_data['pictures'] = [];
        }

        $this->_data['pictures'][] = new Picture([
            'filepath' => $filepath,
            'description' => $description,
            'order' => is_null($order) ? ++$this->_imagesCounter : $order,
            'default' => $default
        ], $this->_ico);
    }

    /**
     * Add category
     *
     * @param int category id
     * @return void
     */
    public function addCategory($categoryId)
    {
        if (!isset($this->_data['categories'])) {
            $this->_data['categories'] = [];
        }

        $this->_data['categories'][] = new Category([
            'idCategory' => $categoryId
        ], $this->_ico);
    }

    /**
     * Add int parameter
     *
     * @param int parameter id
     * @param string type
     * @param mixed value
     * @return void
     */
    public function addIntParameter($parameterId, $type, $value)
    {
        if (!isset($this->_data['intParameters'])) {
            $this->_data['intParameters'] = [];
        }

        $this->_data['intParameters'][] = new IntParameter([
            'intParameterID' => $parameterId,
            'intParameterType' => $type,
            'value' => $value
        ], $this->_ico);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('stk:stockHeader', null, $this->_namespace('stk'));

        $this->_addElements($xml, array_merge($this->_elements, ['categories', 'pictures', 'parameters', 'intParameters']), 'stk');

        return $xml;
    }
}
