<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace spec\Riesenia;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Riesenia\Pohoda\Stock;

class PohodaSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('123');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Riesenia\Pohoda');
    }

    public function it_throws_exception_on_wrong_agenda_name()
    {
        $this->shouldThrow('DomainException')->during('create', [Argument::any()]);
    }

    public function it_creates_existing_objects()
    {
        $this->create('Stock', [
            'code' => 'CODE',
            'name' => 'NAME',
            'storage' => 'STORAGE',
            'typePrice' => ['id' => 1]
        ])->shouldBeAnInstanceOf('Riesenia\Pohoda\Stock');
    }

    #public function it_can_write_file(Stock $stock)
    public function it_can_write_file()
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'xml');
        $stock = new Stock([
            'code' => 'CODE',
            'name' => 'NAME',
            'storage' => 'STORAGE',
            'typePrice' => ['id' => 1]
        ], '123');

        $this->open($tmpFile, 'ABC')->shouldReturn(true);
        $this->addItem('ITEM_ID', $stock);
        $this->close();

        $xml = simplexml_load_file($tmpFile);

        // test dataPack properties
        expect((string) $xml['id'])->toBe('ABC');
        expect((string) $xml['ico'])->toBe('123');
        expect((string) $xml['note'])->toBe('');

        // test dataPackItem properties
        expect((string) $xml->children('dat', true)->dataPackItem->attributes()['id'])->toBe('ITEM_ID');

        unlink($tmpFile);
    }
}
