<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace spec\Riesenia\Pohoda\ValueTransformer;

use PhpSpec\ObjectBehavior;
use Riesenia\Pohoda\ValueTransformer\CyrillicTransliterationTransformer;

class CyrillicTransliterationTransformerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CyrillicTransliterationTransformer::class);
    }

    public function it_transforms_cyrillic_characters()
    {
        $this->transform('Привет мир!')->shouldReturn('Privet mir!');
    }

    public function it_keeps_czech_characters()
    {
        $this->transform('Příliš žluťoučký kůň úpěl ďábelské ódy')->shouldReturn('Příliš žluťoučký kůň úpěl ďábelské ódy');
    }
}
