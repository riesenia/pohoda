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
     * @param self $category
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

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('ctg:categoryDetail', '', $this->_namespace('ctg'));
        $xml->addAttribute('version', '2.0');

        $this->categoryXML($xml);

        return $xml;
    }

    /**
     * Attach category to XML element.
     *
     * @param \SimpleXMLElement $xml
     *
     * @return void
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

    /**
     * {@inheritdoc}
     */
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

    public function addInternetParam($idInternetParam) {
        if (!isset($this->_data['internetParams'])) {
            $data = ['internetParams' => $idInternetParam];
            $this->_data['internetParams'] = new InternetParams($data, $this->_ico, false);
        }

        $this->_data['internetParams']->addItem($idInternetParam);
    }
}
