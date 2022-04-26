<?php
/**
 * @author Pavel Fiala fiala.pvl@gmail.com
 * @link https://github.com/daddyy/pohoda
 */

declare(strict_types=1);

namespace z41f1t\Extension\Pohoda\Enquiry;

class Summary extends \Riesenia\Pohoda\Offer\Summary
{
    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('enq:enquirySummary', '', $this->_namespace('enq'));

        $this->_addElements($xml, $this->_elements, 'enq');

        return $xml;
    }
}
