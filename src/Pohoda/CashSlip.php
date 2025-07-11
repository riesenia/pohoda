<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda;

class CashSlip extends Document
{
    /** @var string */
    public static $importRoot = 'lst:prodejka';

    protected function _getDocumentNamespace(): string
    {
        return 'pro';
    }

    protected function _getDocumentName(): string
    {
        return 'prodejka';
    }
}
