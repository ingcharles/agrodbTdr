<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for DeliverCountryResponseEnvelopeResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:DeliverCountryResponseEnvelopeResponse
 * @subpackage Structs
 */
class DeliverCountryResponseEnvelopeResponse extends AbstractStructBase
{
    /**
     * The DeliverCountryResponseEnvelopeResult
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \StructType\EnvelopeHeader
     */
    public $DeliverCountryResponseEnvelopeResult;
    /**
     * Constructor method for DeliverCountryResponseEnvelopeResponse
     * @uses DeliverCountryResponseEnvelopeResponse::setDeliverCountryResponseEnvelopeResult()
     * @param \StructType\EnvelopeHeader $deliverCountryResponseEnvelopeResult
     */
    public function __construct(\StructType\EnvelopeHeader $deliverCountryResponseEnvelopeResult = null)
    {
        $this
            ->setDeliverCountryResponseEnvelopeResult($deliverCountryResponseEnvelopeResult);
    }
    /**
     * Get DeliverCountryResponseEnvelopeResult value
     * @return \StructType\EnvelopeHeader|null
     */
    public function getDeliverCountryResponseEnvelopeResult()
    {
        return $this->DeliverCountryResponseEnvelopeResult;
    }
    /**
     * Set DeliverCountryResponseEnvelopeResult value
     * @param \StructType\EnvelopeHeader $deliverCountryResponseEnvelopeResult
     * @return \StructType\DeliverCountryResponseEnvelopeResponse
     */
    public function setDeliverCountryResponseEnvelopeResult(\StructType\EnvelopeHeader $deliverCountryResponseEnvelopeResult = null)
    {
        $this->DeliverCountryResponseEnvelopeResult = $deliverCountryResponseEnvelopeResult;
        return $this;
    }
}
