<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\ValueTransformer;

/**
 * A transformer that returns the input value unchanged.
 *
 * This transformer performs no modifications and simply passes the original value through.
 */
class IdentityTransformer implements ValueTransformer
{
    public function transform(string $value): string
    {
        return $value;
    }
}
