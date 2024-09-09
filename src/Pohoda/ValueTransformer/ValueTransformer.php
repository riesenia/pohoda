<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\ValueTransformer;

interface ValueTransformer
{
    /**
     * Transform data in xml nodes.
     *
     * @param string $value
     */
    public function transform(string $value): string;
}
