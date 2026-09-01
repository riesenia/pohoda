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

class Gpsr extends Agenda
{
    /** @var string */
    public static $importRoot = 'lst:GPSR';

    public function getXML(): \SimpleXMLElement
    {
        throw new \DomainException('GPSR agenda supports only export from Pohoda.');
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
    }
}
