<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda;

// TODO: bikeshed the name before the next verions releases
/**
 * A type used to transform values into xml data while the're being serialized.
 */
interface ValueTransformer
{
    /**
     * Transform data into xml data.
     * 
     * This function should generally be pure and not have any side effects.
     */
    public function transform(string $value): string;
}
