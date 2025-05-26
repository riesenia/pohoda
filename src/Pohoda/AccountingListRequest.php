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
use Riesenia\Pohoda\Filter\QueryFilter;
use Riesenia\Pohoda\ListRequest\Filter;
use Riesenia\Pohoda\ListRequest\Limit;

/**
 * AccountingListRequest - for accounting-specific list requests with agenda support
 * Supports listAccountingSingleEntryRequest and listAccountingDoubleEntryRequest
 */
class AccountingListRequest extends Agenda {
    
    /**
     * Add agenda to the request
     *
     * @param string $agenda
     *
     * @return $this
     */
    public function addAgenda(string $agenda): self {
        if (!isset($this->_data['agendas'])) {
            $this->_data['agendas'] = [];
        }
        $this->_data['agendas'][] = $agenda;

        return $this;
    }

    /**
     * Add multiple agendas to the request
     *
     * @param array<string> $agendas
     *
     * @return $this
     */
    public function addAgendas(array $agendas): self {
        foreach ($agendas as $agenda) {
            $this->addAgenda($agenda);
        }

        return $this;
    }

    /**
     * Add filter.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addFilter(array $data): self {
        $this->_data['filter'] = new Filter($data, $this->_ico);

        return $this;
    }

    /**
     * Add query filter (SQL).
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addQueryFilter(array $data): self {
        $this->_data['queryFilter'] = new QueryFilter($data, $this->_ico);

        return $this;
    }

    /**
     * Add limit.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addLimit(array $data): self {
        $data['namespace'] = 'lst';
        $this->_data['limit'] = new Limit($data, $this->_ico);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement {
        $requestType = $this->_data['accountingType'] === 'singleEntry' ? 'listAccountingSingleEntryRequest' : 'listAccountingDoubleEntryRequest';
        
        $xml = $this->_createXML()->addChild('lst:' . $requestType, '', $this->_namespace('lst'));
        $xml->addAttribute('version', '2.0');

        if (isset($this->_data['limit'])) {
            $this->_addElements($xml, ['limit'], 'lst');
        }

        // Add agendas if specified
        if (!empty($this->_data['agendas'])) {
            $agendasElement = $xml->addChild('lst:agendas');
            foreach ($this->_data['agendas'] as $agenda) {
                $agendasElement->addChild('lst:agenda', $agenda);
            }
        }

        // Add filter and queryFilter if specified
        if (isset($this->_data['filter']) || isset($this->_data['queryFilter'])) {
            $this->_addElements($xml, ['filter', 'queryFilter'], 'ftr');
        }

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefined(['accountingType', 'agendas']);
        $resolver->setRequired('accountingType');
        $resolver->setAllowedValues('accountingType', ['singleEntry', 'doubleEntry']);
        $resolver->setDefault('accountingType', 'singleEntry');
    }
}