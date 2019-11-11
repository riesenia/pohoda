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

class IndividualPrice extends Agenda
{
    /** @var string */
    public static $importRoot = 'lst:individualPrice';

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        throw new \DomainException('Individual prices import is currently not supported.');
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
    }
}
