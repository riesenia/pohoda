<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda\Common\AddActionTypeTrait;

class Order extends Document
{
    use AddActionTypeTrait;

    /** @var string */
    public static $importRoot = 'lst:order';

    protected function _getDocumentElements(): array
    {
        return \array_merge(['actionType'], parent::_getDocumentElements());
    }

    protected function _getDocumentNamespace(): string
    {
        return 'ord';
    }

    protected function _getDocumentName(): string
    {
        return 'order';
    }
}
