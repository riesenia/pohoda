<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda;

class IntDoc extends Document
{
    /** @var string */
    public static $importRoot = 'lst:intDoc';

    protected function _getDocumentNamespace(): string
    {
        return 'int';
    }

    protected function _getDocumentName(): string
    {
        return 'intDoc';
    }
}
