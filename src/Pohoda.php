<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia;

use Riesenia\Pohoda\Agenda;

/**
 * Factory for Pohoda objects.
 *
 * @method \Riesenia\Pohoda\Addressbook   createAddressbook(array $data = [])
 * @method \Riesenia\Pohoda\Bank          createBank(array $data = [])
 * @method \Riesenia\Pohoda\CashSlip      createCashSlip(array $data = [])
 * @method \Riesenia\Pohoda\Category      createCategory(array $data = [])
 * @method \Riesenia\Pohoda\Contract      createContract(array $data = [])
 * @method \Riesenia\Pohoda\IntDoc        createIntDoc(array $data = [])
 * @method \Riesenia\Pohoda\IntParam      createIntParam(array $data = [])
 * @method \Riesenia\Pohoda\Invoice       createInvoice(array $data = [])
 * @method \Riesenia\Pohoda\IssueSlip     createIssueSlip(array $data = [])
 * @method \Riesenia\Pohoda\ListRequest   createListRequest(array $data = [])
 * @method \Riesenia\Pohoda\Offer         createOffer(array $data = [])
 * @method \Riesenia\Pohoda\Order         createOrder(array $data = [])
 * @method \Riesenia\Pohoda\PrintRequest  createPrintRequest(array $data = [])
 * @method \Riesenia\Pohoda\Receipt       createReceipt(array $data = [])
 * @method \Riesenia\Pohoda\Stock         createStock(array $data = [])
 * @method \Riesenia\Pohoda\StockTransfer createStockTransfer(array $data = [])
 * @method \Riesenia\Pohoda\Storage       createStorage(array $data = [])
 * @method \Riesenia\Pohoda\Supplier      createSupplier(array $data = [])
 * @method \Riesenia\Pohoda\UserList      createUserList(array $data = [])
 */
class Pohoda
{
    /** @var array<string,string> */
    public static $namespaces = [
        'adb' => 'http://www.stormware.cz/schema/version_2/addressbook.xsd',
        'bnk' => 'http://www.stormware.cz/schema/version_2/bank.xsd',
        'con' => 'http://www.stormware.cz/schema/version_2/contract.xsd',
        'ctg' => 'http://www.stormware.cz/schema/version_2/category.xsd',
        'dat' => 'http://www.stormware.cz/schema/version_2/data.xsd',
        'ftr' => 'http://www.stormware.cz/schema/version_2/filter.xsd',
        'int' => 'http://www.stormware.cz/schema/version_2/intDoc.xsd',
        'inv' => 'http://www.stormware.cz/schema/version_2/invoice.xsd',
        'ipm' => 'http://www.stormware.cz/schema/version_2/intParam.xsd',
        'lAdb' => 'http://www.stormware.cz/schema/version_2/list_addBook.xsd',
        'lst' => 'http://www.stormware.cz/schema/version_2/list.xsd',
        'lStk' => 'http://www.stormware.cz/schema/version_2/list_stock.xsd',
        'ofr' => 'http://www.stormware.cz/schema/version_2/offer.xsd',
        'ord' => 'http://www.stormware.cz/schema/version_2/order.xsd',
        'pre' => 'http://www.stormware.cz/schema/version_2/prevodka.xsd',
        'pri' => 'http://www.stormware.cz/schema/version_2/prijemka.xsd',
        'prn' => 'http://www.stormware.cz/schema/version_2/print.xsd',
        'pro' => 'http://www.stormware.cz/schema/version_2/prodejka.xsd',
        'str' => 'http://www.stormware.cz/schema/version_2/storage.xsd',
        'stk' => 'http://www.stormware.cz/schema/version_2/stock.xsd',
        'sup' => 'http://www.stormware.cz/schema/version_2/supplier.xsd',
        'typ' => 'http://www.stormware.cz/schema/version_2/type.xsd',
        'vyd' => 'http://www.stormware.cz/schema/version_2/vydejka.xsd'
    ];

    /** @var string */
    public static $encoding = 'windows-1250';

    /** @var bool */
    public static $sanitizeEncoding = false;

    /**
     * A set of transformers that will be used when serializing data.
     *
     * @var \Riesenia\Pohoda\ValueTransformer\ValueTransformer[]
     */
    public static $transformers = [];

    /** @var string */
    protected $_ico;

    /** @var string */
    protected $_application = 'Rshop Pohoda connector';

    /** @var bool */
    protected $_isInMemory;

    /** @var \XMLWriter */
    protected $_xmlWriter;

    /** @var \XMLReader */
    protected $_xmlReader;

    /** @var string */
    protected $_elementName;

    /** @var bool */
    protected $_importRecursive;

    /**
     * Constructor.
     *
     * @param string $ico
     */
    public function __construct($ico)
    {
        $this->_ico = $ico;
    }

    /**
     * Set the name of the application.
     *
     * @param string $name
     *
     * @return void
     */
    public function setApplicationName(string $name)
    {
        $this->_application = $name;
    }

    /**
     * Create and return instance of requested agenda.
     *
     * @param string              $name
     * @param array<string,mixed> $data
     *
     * @return Agenda
     */
    public function create(string $name, array $data = []): Agenda
    {
        $fullName = __NAMESPACE__ . '\\Pohoda\\' . $name;

        if (!\class_exists($fullName)) {
            throw new \DomainException('Not allowed entity: ' . $name);
        }

        return new $fullName($data, $this->_ico);
    }

    /**
     * Open new XML file for writing.
     *
     * @param string|null $filename path to output file or null for memory
     * @param string      $id
     * @param string      $note
     *
     * @return bool
     */
    public function open(?string $filename, string $id, string $note = ''): bool
    {
        $this->_xmlWriter = new \XMLWriter();

        if ($filename === null) {
            $this->_isInMemory = true;
            $this->_xmlWriter->openMemory();
        } else {
            $this->_isInMemory = false;

            if (!$this->_xmlWriter->openUri($filename)) {
                return false;
            }
        }

        $this->_xmlWriter->startDocument('1.0', self::$encoding);
        $this->_xmlWriter->startElementNs('dat', 'dataPack', null);

        $this->_xmlWriter->writeAttribute('id', $id);
        $this->_xmlWriter->writeAttribute('ico', $this->_ico);
        $this->_xmlWriter->writeAttribute('application', $this->_application);
        $this->_xmlWriter->writeAttribute('version', '2.0');
        $this->_xmlWriter->writeAttribute('note', $note);

        foreach (self::$namespaces as $k => $v) {
            $this->_xmlWriter->writeAttributeNs('xmlns', $k, null, $v);
        }

        return true;
    }

    /**
     * Add item.
     *
     * @param string $id
     * @param Agenda $agenda
     *
     * @return void
     */
    public function addItem(string $id, Agenda $agenda)
    {
        $this->_xmlWriter->startElementNs('dat', 'dataPackItem', null);

        $this->_xmlWriter->writeAttribute('id', $id);
        $this->_xmlWriter->writeAttribute('version', '2.0');
        $this->_xmlWriter->writeRaw((string) $agenda->getXML()->asXML());
        $this->_xmlWriter->endElement();

        if (!$this->_isInMemory) {
            $this->_xmlWriter->flush();
        }
    }

    /**
     * End and close XML file.
     *
     * @return int|string written bytes for file or XML string for memory
     */
    public function close()
    {
        $this->_xmlWriter->endElement();

        return $this->_xmlWriter->flush();
    }

    /**
     * Load XML file.
     *
     * @param string $name
     * @param string $filename
     *
     * @return bool
     */
    public function load(string $name, string $filename)
    {
        $this->_xmlReader = new \XMLReader();

        if (!$this->_xmlReader->open($filename)) {
            return false;
        }

        $fullName = __NAMESPACE__ . '\\Pohoda\\' . $name;

        if (!\class_exists($fullName) || !\is_string($fullName::$importRoot)) {
            throw new \DomainException('Not allowed entity: ' . $name);
        }

        $this->_elementName = $fullName::$importRoot;
        $this->_importRecursive = $fullName::$importRecursive;

        return true;
    }

    /**
     * Get next item in loaded file.
     *
     * @return \SimpleXMLElement|null
     */
    public function next()
    {
        while ($this->_xmlReader->nodeType != \XMLReader::ELEMENT || $this->_xmlReader->name !== $this->_elementName) {
            if (!$this->_xmlReader->read()) {
                return null;
            }
        }

        $xml = new \SimpleXMLElement($this->_xmlReader->readOuterXml());
        $this->_importRecursive ? $this->_xmlReader->next() : $this->_xmlReader->read();

        return $xml;
    }

    /**
     * Handle dynamic method calls.
     *
     * @param string  $method
     * @param mixed[] $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        // create<Agenda> method
        if (\preg_match('/create([A-Z][a-zA-Z0-9]*)/', $method, $matches)) {
            return \call_user_func([$this, 'create'], $matches[1], $arguments[0] ?? []);
        }

        // load<Agenda> method
        if (\preg_match('/load([A-Z][a-zA-Z0-9]*)/', $method, $matches)) {
            if (!isset($arguments[0])) {
                throw new \DomainException('Filename not set.');
            }

            return \call_user_func([$this, 'load'], $matches[1], $arguments[0]);
        }

        throw new \BadMethodCallException('Unknown method: ' . $method);
    }
}
