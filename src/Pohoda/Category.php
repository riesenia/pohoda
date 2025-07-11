<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda\Common\OptionsResolver;

class Category extends Agenda
{
    /** @var string */
    public static $importRoot = 'ctg:category';

    /** @var bool */
    public static $importRecursive = true;

    /** @var string[] */
    protected $_elements = ['name', 'description', 'sequence', 'displayed', 'picture', 'note'];

    /**
     * Add subcategory.
     *
     * @return $this
     */
    public function addSubcategory(self $category): self
    {
        if (!isset($this->_data['subCategories'])) {
            $this->_data['subCategories'] = [];
        }

        $this->_data['subCategories'][] = $category;

        return $this;
    }

    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('ctg:categoryDetail', '', $this->_namespace('ctg'));
        $xml->addAttribute('version', '2.0');

        $this->categoryXML($xml);

        return $xml;
    }

    /**
     * Attach category to XML element.
     */
    public function categoryXML(\SimpleXMLElement $xml)
    {
        $category = $xml->addChild('ctg:category', '', $this->_namespace('ctg'));

        $this->_addElements($category, $this->_elements, 'ctg');

        if (isset($this->_data['subCategories'])) {
            $subCategories = $category->addChild('ctg:subCategories', '', $this->_namespace('ctg'));

            foreach ($this->_data['subCategories'] as $subCategory) {
                $subCategory->categoryXML($subCategories);
            }
        }
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setRequired('name');
        $resolver->setNormalizer('name', $resolver->getNormalizer('string48'));
        $resolver->setNormalizer('sequence', $resolver->getNormalizer('int'));
        $resolver->setNormalizer('displayed', $resolver->getNormalizer('bool'));
    }
}
