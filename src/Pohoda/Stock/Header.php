<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Stock;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\AddParameterTrait;
use Riesenia\Pohoda\Common\OptionsResolver;

class Header extends Agenda
{
    use AddParameterTrait;

    /** @var array */
    protected $_refElements = ['storage', 'typePrice', 'typeRP'];

    /** @var array */
    protected $_elementsAttributesMapper = [
        'purchasingPricePayVAT' => ['purchasingPrice', 'payVAT', null],
        'sellingPricePayVAT' => ['sellingPrice', 'payVAT', null]
    ];

    /** @var array */
    protected $_elements = ['stockType', 'code', 'EAN', 'PLU', 'isSales', 'isSerialNumber', 'isInternet', 'isBatch', 'purchasingRateVAT', 'sellingRateVAT', 'name', 'nameComplement', 'unit', 'unit2', 'unit3', 'coefficient2', 'coefficient3', 'storage', 'typePrice', 'purchasingPrice', 'purchasingPricePayVAT', 'sellingPrice', 'sellingPricePayVAT', 'limitMin', 'limitMax', 'mass', 'volume', 'supplier', 'shortName', 'typeRP', 'guaranteeType', 'guarantee', 'producer', 'description', 'description2', 'note'];

    /** @var int */
    protected $_imagesCounter = 0;

    /**
     * Add image.
     *
     * @param string   $filepath
     * @param string   $description
     * @param int|null $order
     * @param bool     $default
     */
    public function addImage(string $filepath, string $description = '', int $order = null, bool $default = false)
    {
        if (!isset($this->_data['pictures'])) {
            $this->_data['pictures'] = [];
        }

        $this->_data['pictures'][] = new Picture([
            'filepath' => $filepath,
            'description' => $description,
            'order' => $order === null ? ++$this->_imagesCounter : $order,
            'default' => $default
        ], $this->_ico);
    }

    /**
     * Add category.
     *
     * @param int $categoryId
     */
    public function addCategory(int $categoryId)
    {
        if (!isset($this->_data['categories'])) {
            $this->_data['categories'] = [];
        }

        $this->_data['categories'][] = new Category([
            'idCategory' => $categoryId
        ], $this->_ico);
    }

    /**
     * Add int parameter.
     *
     * @param array $data
     */
    public function addIntParameter(array $data)
    {
        if (!isset($this->_data['intParameters'])) {
            $this->_data['intParameters'] = [];
        }

        $this->_data['intParameters'][] = new IntParameter($data, $this->_ico);
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('stk:stockHeader', null, $this->_namespace('stk'));

        $this->_addElements($xml, \array_merge($this->_elements, ['categories', 'pictures', 'parameters', 'intParameters']), 'stk');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setDefault('stockType', 'card');
        $resolver->setAllowedValues('stockType', ['card', 'text', 'service', 'package', 'set', 'product']);
        $resolver->setNormalizer('isSales', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('isSerialNumber', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('isInternet', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('isBatch', $resolver->getNormalizer('bool'));
        $resolver->setAllowedValues('purchasingRateVAT', ['none', 'low', 'high']);
        $resolver->setAllowedValues('sellingRateVAT', ['none', 'low', 'high']);
        $resolver->setNormalizer('name', $resolver->getNormalizer('string90'));
        $resolver->setNormalizer('nameComplement', $resolver->getNormalizer('string90'));
        $resolver->setNormalizer('unit', $resolver->getNormalizer('string10'));
        $resolver->setNormalizer('unit2', $resolver->getNormalizer('string10'));
        $resolver->setNormalizer('unit3', $resolver->getNormalizer('string10'));
        $resolver->setNormalizer('coefficient2', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('coefficient3', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('purchasingPrice', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('purchasingPricePayVAT', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('sellingPrice', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('sellingPricePayVAT', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('limitMin', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('limitMax', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('mass', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('volume', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('shortName', $resolver->getNormalizer('string24'));
        $resolver->setAllowedValues('guaranteeType', ['none', 'hour', 'day', 'month', 'year', 'life']);
        $resolver->setNormalizer('guarantee', $resolver->getNormalizer('int'));
        $resolver->setNormalizer('producer', $resolver->getNormalizer('string90'));
        $resolver->setNormalizer('description', $resolver->getNormalizer('string240'));
    }
}
