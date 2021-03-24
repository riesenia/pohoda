<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\PrintRequest;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class Record extends Agenda
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process filter
        $data['filter'] = new Filter($data['filter'], $ico, $resolveOptions);

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('prn:record', '', $this->_namespace('prn'));
        $xml->addAttribute('agenda', $this->_data['agenda']);

        $this->_addElements($xml, ['filter'], 'prn');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['agenda', 'filter']);

        $resolver->setRequired('agenda');
        $resolver->setRequired('filter');
    }
}
