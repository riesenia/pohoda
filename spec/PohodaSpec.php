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
use Riesenia\Pohoda;
use Riesenia\Pohoda\Stock;
use Riesenia\Pohoda\ValueTransformer\ValueTransformer;

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

    public function it_can_write_file()
    {
        $tmpFile = \tempnam(\sys_get_temp_dir(), 'xml');

        $stock = new Stock([
            'code' => 'CODE',
            'name' => 'NAME',
            'storage' => 'STORAGE',
            'typePrice' => ['id' => 1]
        ], '123');

        $this->open($tmpFile, 'ABC')->shouldReturn(true);
        $this->addItem('ITEM_ID', $stock);
        $this->close();

        $xml = \simplexml_load_file($tmpFile);

        // test dataPack properties
        expect((string) $xml['id'])->toBe('ABC');
        expect((string) $xml['ico'])->toBe('123');
        expect((string) $xml['note'])->toBe('');

        // test dataPackItem properties
        expect((string) $xml->children('dat', true)->dataPackItem->attributes()['id'])->toBe('ITEM_ID');

        \unlink($tmpFile);
    }

    public function it_can_write_to_memory()
    {
        $stock = new Stock([
            'code' => 'CODE',
            'name' => 'NAME',
            'storage' => 'STORAGE',
            'typePrice' => ['id' => 1]
        ], '123');

        $this->open(null, 'ABC')->shouldReturn(true);
        $this->addItem('ITEM_ID', $stock);

        $xml = \simplexml_load_string($this->close()->getWrappedObject());

        // test dataPack properties
        expect((string) $xml['id'])->toBe('ABC');
        expect((string) $xml['ico'])->toBe('123');
        expect((string) $xml['note'])->toBe('');

        // test dataPackItem properties
        expect((string) $xml->children('dat', true)->dataPackItem->attributes()['id'])->toBe('ITEM_ID');
    }

    public function it_processes_recursive_export_correctly()
    {
        $tmpFile = \tempnam(\sys_get_temp_dir(), 'xml');

        \file_put_contents($tmpFile, '<?xml version="1.0" encoding="Windows-1250"?>
        <rsp:responsePack version="2.0" id="002" state="ok" note="" xmlns:rsp="http://www.stormware.cz/schema/version_2/response.xsd" xmlns:lst="http://www.stormware.cz/schema/version_2/list.xsd" xmlns:ctg="http://www.stormware.cz/schema/version_2/category.xsd">
            <rsp:responsePackItem version="2.0" id="a56" state="ok">
                <lst:listCategory version="2.0" state="ok">
                    <lst:categoryDetail version="2.0">
                        <ctg:category>
                            <ctg:id>1</ctg:id>
                            <ctg:name>Kategorie-A</ctg:name>
                            <ctg:description/>
                            <ctg:sequence>0</ctg:sequence>
                            <ctg:displayed>true</ctg:displayed>
                            <ctg:picture/>
                            <ctg:note/>
                            <ctg:internetParams>
                                <ctg:idInternetParams>3</ctg:idInternetParams>
                            </ctg:internetParams>
                            <ctg:subCategories>
                                <ctg:category>
                                    <ctg:id>2</ctg:id>
                                    <ctg:name>Kategorie-B</ctg:name>
                                    <ctg:description>testovaci kategorie B</ctg:description>
                                    <ctg:sequence>1</ctg:sequence>
                                    <ctg:displayed>true</ctg:displayed>
                                    <ctg:picture/>
                                    <ctg:note/>
                                    <ctg:internetParams>
                                        <ctg:idInternetParams>1</ctg:idInternetParams>
                                    </ctg:internetParams>
                                </ctg:category>
                                <ctg:category>
                                    <ctg:id>3</ctg:id>
                                    <ctg:name>Kategorie-C</ctg:name>
                                    <ctg:description>testovaci kategorie C</ctg:description>
                                    <ctg:sequence>2</ctg:sequence>
                                    <ctg:displayed>true</ctg:displayed>
                                    <ctg:picture/>
                                    <ctg:note/>
                                    <ctg:internetParams>
                                        <ctg:idInternetParams>2</ctg:idInternetParams>
                                    </ctg:internetParams>
                                </ctg:category>
                            </ctg:subCategories>
                        </ctg:category>
                        <ctg:category>
                            <ctg:id>4</ctg:id>
                            <ctg:name>Kategorie-D</ctg:name>
                            <ctg:description>testovaci kategorie D</ctg:description>
                            <ctg:sequence>0</ctg:sequence>
                            <ctg:displayed>true</ctg:displayed>
                            <ctg:picture/>
                            <ctg:note/>
                            <ctg:internetParams>
                                <ctg:idInternetParams/>
                            </ctg:internetParams>
                        </ctg:category>
                    </lst:categoryDetail>
                </lst:listCategory>
            </rsp:responsePackItem>
        </rsp:responsePack>');

        $this->loadCategory($tmpFile);

        // read only root elements
        $c = $this->next();
        expect((string) $c->getWrappedObject()->children('ctg', true)->name)->toBe('Kategorie-A');
        $c = $this->next();
        expect((string) $c->getWrappedObject()->children('ctg', true)->name)->toBe('Kategorie-D');
        $c = $this->next();
        expect($c->getWrappedObject())->toBe(null);
    }

    public function it_runs_transformers_properly()
    {
        $stock = new Stock([
            'code' => 'code1',
            'name' => 'name2',
            'storage' => 'storage3',
            'typePrice' => ['id' => 4]
        ], '123');

        Pohoda::$transformers = [new Capitalizer()];

        $this->open(null, 'ABC')->shouldReturn(true);
        $this->addItem('item_id', $stock);

        $xml = \simplexml_load_string($this->close()->getWrappedObject());

        expect((string) $xml->xpath('//stk:code')[0])->toBe('CODE1');
        expect((string) $xml->xpath('//stk:name')[0])->toBe('NAME2');
        expect((string) $xml->xpath('//typ:ids')[0])->toBe('STORAGE3');

        // Don't add transformers to other tests
        Pohoda::$transformers = [];
    }

    public function it_handles_static_arrays_correctly()
    {
        $stock = new Stock([
            'code' => 'code1',
            'name' => 'name2',
            'storage' => 'storage3',
            'typePrice' => ['id' => 4]
        ], '123');

        Pohoda::$sanitizeEncoding = true;

        $this->open(null, 'ABC')->shouldReturn(true);
        $this->addItem('item_id', $stock);
        expect(\count(Pohoda::$transformers))->toBe(0);
        $this->close();

        // Don't sanitize in any other test
        Pohoda::$sanitizeEncoding = false;
    }
}

class Capitalizer implements ValueTransformer
{
    public function transform(string $value): string
    {
        return \strtoupper($value);
    }
}
