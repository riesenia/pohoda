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
use Riesenia\Pohoda\ListRequest\Limit;
use Riesenia\Pohoda\ListTableExport\RequestTableExport;

class ListTableExport extends Agenda
{
    /** @var string */
    public static $importRoot = 'lst:tableExport';

    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process requestTableExport
        if (isset($data['requestTableExport'])) {
            $data['requestTableExport'] = new RequestTableExport($data['requestTableExport'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Add limit.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addLimit(array $data): self
    {
        $data['namespace'] = 'lst';
        $this->_data['limit'] = new Limit($data, $this->_ico);

        return $this;
    }

    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('lst:listTableExportRequest', '', $this->_namespace('lst'));
        $xml->addAttribute('version', '2.0');
        $xml->addAttribute('tableExportVersion', '2.0');

        if (isset($this->_data['limit'])) {
            $this->_addElements($xml, ['limit'], 'lst');
        }

        $this->_addElements($xml, ['requestTableExport'], 'lst');

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['requestTableExport', 'limit']);

        // validate / format options
        $resolver->setRequired('requestTableExport');
    }
}
