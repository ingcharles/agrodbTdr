<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for DeliverEnvelopeResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:DeliverEnvelopeResponse
 * @subpackage Structs
 */
class DeliverEnvelopeResponse extends AbstractStructBase
{
    /**
     * The DeliverEnvelopeResult
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \StructType\EnvelopeHeader
     */
    public $DeliverEnvelopeResult;
    /**
     * Constructor method for DeliverEnvelopeResponse
     * @uses DeliverEnvelopeResponse::setDeliverEnvelopeResult()
     * @param \StructType\EnvelopeHeader $deliverEnvelopeResult
     */
    public function __construct(\StructType\EnvelopeHeader $deliverEnvelopeResult = null)
    {
        $this
            ->setDeliverEnvelopeResult($deliverEnvelopeResult);
    }
    /**
     * Get DeliverEnvelopeResult value
     * @return \StructType\EnvelopeHeader|null
     */
    public function getDeliverEnvelopeResult()
    {
        return $this->DeliverEnvelopeResult;
    }
    /**
     * Set DeliverEnvelopeResult value
     * @param \StructType\EnvelopeHeader $deliverEnvelopeResult
     * @return \StructType\DeliverEnvelopeResponse
     */
    public function setDeliverEnvelopeResult(\StructType\EnvelopeHeader $deliverEnvelopeResult = null)
    {
        $this->DeliverEnvelopeResult = $deliverEnvelopeResult;
        return $this;
    }
}
