<?php

/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\ValueTransformer;

use Normalizer;
use Riesenia\Pohoda\ValueTransformer;

class CyrillicTransliterationTransformer implements ValueTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform(string $value): string
    {
        $normalized = Normalizer::normalize($value, Normalizer::FORM_C);

        if ($normalized === false) {
            // If normalization failed the text probably isn't valid utf-8
            // Since we can't really do anything about it let's just bail.
            return $value;
        }

        $transformer = \Transliterator::create('Any-Latin; Latin-ASCII');

        if ($transformer == null) {
            return $value;
        }

        $chars = \preg_split('//u', $normalized, -1, PREG_SPLIT_NO_EMPTY);

        if ($chars === false) {
            // As far as I know preg_split can only fail when the regex pattern
            // doesn't compile. If so something else has gone wrong. Let's bail.
            return $value;
        }

        $result = '';

        foreach ($chars as $char) {
            $codePoint = \mb_ord($char, 'UTF-8');

            if (($codePoint >= 0x00400 && $codePoint <= 0x004FF)    // Cyrillic Basic
                || ($codePoint >= 0x00500 && $codePoint <= 0x0052F) // Cyrillic Supplement
                || ($codePoint >= 0x02DE0 && $codePoint <= 0x02DFF) // Cyrillic Extended-A
                || ($codePoint >= 0x0A640 && $codePoint <= 0x0A69F) // Cyrillic Extended-B
                || ($codePoint >= 0x01C80 && $codePoint <= 0x01C8F) // Cyrillic Extended-C
                || ($codePoint >= 0x1E030 && $codePoint <= 0x1E08F) // Cyrillic Extended-D
            ) {
                $result .= $transformer->transliterate($char);
            } else {
                $result .= $char;
            }
        }

        return $result;
    }
}
