<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace spec\Riesenia\Pohoda;

use PhpSpec\ObjectBehavior;
use Riesenia\Pohoda\Category;

class CategorySpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'name' => 'Main',
            'sequence' => 1,
            'displayed' => true
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Riesenia\Pohoda\Category');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<ctg:categoryDetail version="2.0"><ctg:category><ctg:name>Main</ctg:name><ctg:sequence>1</ctg:sequence><ctg:displayed>true</ctg:displayed></ctg:category></ctg:categoryDetail>');
    }

    public function it_can_add_subcategories()
    {
        $sub = new Category([
            'name' => 'Sub',
            'sequence' => 1,
            'displayed' => true
        ], '123');

        $subsub = new Category([
            'name' => 'SubSub',
            'sequence' => 1,
            'displayed' => false
        ], '123');

        $sub->addSubcategory($subsub);

        $sub2 = new Category([
            'name' => 'Sub2',
            'sequence' => '2',
            'displayed' => true
        ], '123');

        $this->addSubcategory($sub);
        $this->addSubcategory($sub2);

        $this->getXML()->asXML()->shouldReturn('<ctg:categoryDetail version="2.0"><ctg:category><ctg:name>Main</ctg:name><ctg:sequence>1</ctg:sequence><ctg:displayed>true</ctg:displayed><ctg:subCategories><ctg:category><ctg:name>Sub</ctg:name><ctg:sequence>1</ctg:sequence><ctg:displayed>true</ctg:displayed><ctg:subCategories><ctg:category><ctg:name>SubSub</ctg:name><ctg:sequence>1</ctg:sequence><ctg:displayed>false</ctg:displayed></ctg:category></ctg:subCategories></ctg:category><ctg:category><ctg:name>Sub2</ctg:name><ctg:sequence>2</ctg:sequence><ctg:displayed>true</ctg:displayed></ctg:category></ctg:subCategories></ctg:category></ctg:categoryDetail>');
    }
}
