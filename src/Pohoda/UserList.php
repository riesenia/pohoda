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
use Riesenia\Pohoda\UserList\ItemUserCode;

class UserList extends Agenda
{
    /** @var string */
    public static $importRoot = 'lst:listUserCode';

    /**
     * Add item user code.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addItemUserCode(array $data): self
    {
        if (!isset($this->_data['itemUserCodes'])) {
            $this->_data['itemUserCodes'] = [];
        }

        $this->_data['itemUserCodes'][] = new ItemUserCode($data, $this->_ico);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('lst:listUserCode', '', $this->_namespace('lst'));
        $xml->addAttribute('version', '1.1');
        $xml->addAttribute('code', $this->_data['code']);
        $xml->addAttribute('name', $this->_data['name']);

        if (isset($this->_data['constants']) && $this->_data['constants'] == 'true') {
            $xml->addAttribute('constants', $this->_data['constants']);
        }

        if (isset($this->_data['itemUserCodes'])) {
            foreach ($this->_data['itemUserCodes'] as $itemUserCode) {
                $this->_appendNode($xml, $itemUserCode->getXML());
            }
        }

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['code', 'name', 'constants']);

        // validate / format options
        $resolver->setRequired('code');
        $resolver->setRequired('name');
        $resolver->setNormalizer('constants', $resolver->getNormalizer('bool'));
    }
}
