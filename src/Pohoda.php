<?php
namespace Rshop\Synchronization;

use Rshop\Synchronization\Pohoda\Agenda;

/**
 * Factory for Pohoda objects
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Pohoda
{
    /**
     * All avaailable namespaces
     *
     * @var array
     */
    public static $namespaces = [
        'adb' => 'http://www.stormware.cz/schema/version_2/addressbook.xsd',
        'con' => 'http://www.stormware.cz/schema/version_2/contract.xsd',
        'ctg' => 'http://www.stormware.cz/schema/version_2/category.xsd',
        'dat' => 'http://www.stormware.cz/schema/version_2/data.xsd',
        'ftr' => 'http://www.stormware.cz/schema/version_2/filter.xsd',
        'inv' => 'http://www.stormware.cz/schema/version_2/invoice.xsd',
        'ipm' => 'http://www.stormware.cz/schema/version_2/intParam.xsd',
        'ord' => 'http://www.stormware.cz/schema/version_2/order.xsd',
        'pre' => 'http://www.stormware.cz/schema/version_2/prevodka.xsd',
        'str' => 'http://www.stormware.cz/schema/version_2/storage.xsd',
        'stk' => 'http://www.stormware.cz/schema/version_2/stock.xsd',
        'typ' => 'http://www.stormware.cz/schema/version_2/type.xsd',
        'vyd' => 'http://www.stormware.cz/schema/version_2/vydejka.xsd'
    ];

    /**
     * ICO
     *
     * @var string
     */
    protected $_ico;

    /**
     * XML object
     *
     * @var \XMLWriter
     */
    protected $_xml;

    /**
     * Constructor
     *
     * @param string ICO
     * @param string api key
     */
    public function __construct($ico)
    {
        $this->_ico = $ico;
    }

    /**
     * Create and return instance of requested agenda
     *
     * @param string agenda name
     * @param string optional data
     * @return Rshop\Synchronization\Pohoda\Agenda
     */
    public function create($name, $data = array())
    {
        $fullName = __NAMESPACE__ . '\\Pohoda\\' . $name;

        if (!class_exists($fullName)) {
            throw new \DomainException("Not allowed entity: " . $name);
        }

        return new $fullName($data, $this->_ico);
    }

    /**
     * Open new XML file for writing
     *
     * @param string filename
     * @param string xml attribute id
     * @param string xml attribute note
     * @return bool
     */
    public function open($filename, $id, $note = '')
    {
        $this->_xml = new \XMLWriter();

        if (!$this->_xml->openURI($filename)) {
            return false;
        }

        $this->_xml->startDocument('1.0', 'windows-1250');
        $this->_xml->startElementNS('dat', 'dataPack', null);

        $this->_xml->writeAttribute('id', $id);
        $this->_xml->writeAttribute('ico', $this->_ico);
        $this->_xml->writeAttribute('application', 'Rshop Pohoda connector');
        $this->_xml->writeAttribute('version', '2.0');
        $this->_xml->writeAttribute('note', $note);

        foreach (self::$namespaces as $k => $v) {
            $this->_xml->writeAttributeNS('xmlns', $k, null, $v);
        }

        return true;
    }

    /**
     * Add item
     *
     * @param string id
     * @param \Rshop\Synchronization\Pohoda\Agenda
     * @return void
     */
    public function addItem($id, Agenda $agenda)
    {
        $this->_xml->startElementNS('dat', 'dataPackItem', null);

        $this->_xml->writeAttribute('id', $id);
        $this->_xml->writeAttribute('version', '2.0');

        $xml = $agenda->getXML();
        if ($xml instanceof \SimpleXMLElement) {
            $this->_xml->writeRaw($xml->asXML());
        }

        $this->_xml->endElement();
        $this->_xml->flush();
    }

    /**
     * End and close XML file
     *
     * @return bool
     */
    public function close()
    {
        $this->_xml->endElement();
        $this->_xml->flush();
    }

    /**
     * Load XML file
     *
     * @param string agenda name
     * @param string filename
     * @return bool
     */
    public function load($name, $filename)
    {
        $this->_xml = new \XMLReader();

        if (!$this->_xml->open($filename)) {
            return false;
        }

        $fullName = __NAMESPACE__ . '\\Pohoda\\' . $name;

        if (!class_exists($fullName)) {
            throw new \DomainException("Not allowed entity: " . $name);
        }

        while ($this->_xml->read() && $this->_xml->name !== $fullName::$importRoot) {
            // skip to first element
        }

        return true;
    }

    /**
     * Get next item in loaded file
     *
     * @return \SimpleXMLElement
     */
    public function next()
    {
        if (!$this->_xml->name) {
            return false;
        }

        $name = $this->_xml->name;

        $node = new \SimpleXMLElement($this->_xml->readOuterXML());

        while ($this->_xml->next() && $this->_xml->name !== $name) {
            // skip to next element
        }

        return $node;
    }

    /**
     * Handle dynamic method calls
     *
     * @param string method name
     * @param array arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        // create<Agenda> method
        if (preg_match('/create([A-Z][a-zA-Z0-9]*)/', $method, $matches)) {
            return call_user_func(array($this, 'create'), $matches[1], isset($arguments[0]) ? $arguments[0] : array());
        }

        // load<Agenda> method
        if (preg_match('/load([A-Z][a-zA-Z0-9]*)/', $method, $matches)) {
            if (!isset($arguments[0])) {
                throw new \DomainException("Filename not set.");
            }

            return call_user_func(array($this, 'load'), $matches[1], $arguments[0]);
        }

        throw new \BadMethodCallException("Unknown method: " . $method);
    }
}
