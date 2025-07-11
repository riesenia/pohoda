<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda;

class Offer extends Document
{
    /** @var string */
    public static $importRoot = 'lst:offer';

    protected function _getDocumentNamespace(): string
    {
        return 'ofr';
    }

    protected function _getDocumentName(): string
    {
        return 'offer';
    }
}
