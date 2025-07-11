<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda;

class Receipt extends Document
{
    /** @var string */
    public static $importRoot = 'lst:prijemka';

    protected function _getDocumentNamespace(): string
    {
        return 'pri';
    }

    protected function _getDocumentName(): string
    {
        return 'prijemka';
    }
}
