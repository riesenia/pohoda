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
use Riesenia\Pohoda\PrintRequest\PrinterSettings;
use Riesenia\Pohoda\PrintRequest\Record;

class PrintRequest extends Agenda
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process record
        $data['record'] = new Record($data['record'], $ico, $resolveOptions);

        // process printer settings
        $data['printerSettings'] = new PrinterSettings($data['printerSettings'], $ico, $resolveOptions);

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('prn:print', '', $this->_namespace('prn'));
        $xml->addAttribute('version', '1.0');

        $this->_addElements($xml, ['record', 'printerSettings'], 'prn');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['record', 'printerSettings']);

        $resolver->setRequired('record');
        $resolver->setRequired('printerSettings');
    }
}
