<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for DeliverPhytoEnvelopeResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:DeliverPhytoEnvelopeResponse
 * @subpackage Structs
 */
class DeliverPhytoEnvelopeResponse extends AbstractStructBase
{
    /**
     * The DeliverPhytoEnvelopeResult
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \StructType\EnvelopeHeader
     */
    public $DeliverPhytoEnvelopeResult;
    /**
     * Constructor method for DeliverPhytoEnvelopeResponse
     * @uses DeliverPhytoEnvelopeResponse::setDeliverPhytoEnvelopeResult()
     * @param \StructType\EnvelopeHeader $deliverPhytoEnvelopeResult
     */
    public function __construct(\StructType\EnvelopeHeader $deliverPhytoEnvelopeResult = null)
    {
        $this
            ->setDeliverPhytoEnvelopeResult($deliverPhytoEnvelopeResult);
    }
    /**
     * Get DeliverPhytoEnvelopeResult value
     * @return \StructType\EnvelopeHeader|null
     */
    public function getDeliverPhytoEnvelopeResult()
    {
        return $this->DeliverPhytoEnvelopeResult;
    }
    /**
     * Set DeliverPhytoEnvelopeResult value
     * @param \StructType\EnvelopeHeader $deliverPhytoEnvelopeResult
     * @return \StructType\DeliverPhytoEnvelopeResponse
     */
    public function setDeliverPhytoEnvelopeResult(\StructType\EnvelopeHeader $deliverPhytoEnvelopeResult = null)
    {
        $this->DeliverPhytoEnvelopeResult = $deliverPhytoEnvelopeResult;
        return $this;
    }
}
