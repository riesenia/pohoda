<?php

namespace spec\Riesenia\Pohoda\ValueTransformer;

use PhpSpec\ObjectBehavior;
use Riesenia\Pohoda\ValueTransformer\CyrillicTransliterationTransformer;

class CyrillicTransliterationTransformerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CyrillicTransliterationTransformer::class);
    }

    function it_transforms_cyrillic_characters()
    {
        $this->transform('Привет мир!')->shouldReturn('Privet mir!');
    }

    function it_keeps_czech_characters()
    {
        $this->transform('Příliš žluťoučký kůň úpěl ďábelské ódy')->shouldReturn('Příliš žluťoučký kůň úpěl ďábelské ódy');
    }
}
