<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda;

class Bank extends Document
{
    /** @var string */
    public static $importRoot = 'lst:bank';

    protected function _getDocumentNamespace(): string
    {
        return 'bnk';
    }

    protected function _getDocumentName(): string
    {
        return 'bank';
    }
}
