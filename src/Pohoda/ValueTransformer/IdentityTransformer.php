<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\ValueTransformer;

use Riesenia\Pohoda\ValueTransformer;

/**
 * A transformer that just returns the same value as was passed in.
 */
class IdentityTransformer implements ValueTransformer
{
    public function transform(string $value): string
    {
        return $value;
    }
}
