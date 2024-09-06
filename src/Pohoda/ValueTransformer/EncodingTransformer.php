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
 * A transformer that converts the encoding of a string.
 * 
 * Since most transformers will expect utf-8 formated strings, this transformer should either be the last to run or be immediately
 * by another EncodingTransformer that will convert the string back to utf-8.
 */
class EncodingTransformer implements ValueTransformer
{
    private $fromEncoding;
    private $toEncoding;

    /**
     * @param string $fromEncoding
     * @param string $toEncoding
     */
    public function __construct(string $fromEncoding, string $toEncoding)
    {
        $this->fromEncoding = $fromEncoding;
        $this->toEncoding = $toEncoding;
    }

    /**
     * {@inheritdoc}
     */
    public function transform(string $value): string
    {
        return iconv($this->fromEncoding, $this->toEncoding, $value);
    }
}