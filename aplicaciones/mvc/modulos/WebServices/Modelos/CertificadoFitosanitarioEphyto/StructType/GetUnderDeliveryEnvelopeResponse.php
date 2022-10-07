<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetUnderDeliveryEnvelopeResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetUnderDeliveryEnvelopeResponse
 * @subpackage Structs
 */
class GetUnderDeliveryEnvelopeResponse extends AbstractStructBase
{
    /**
     * The GetUnderDeliveryEnvelopeResult
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \ArrayType\ArrayOfEnvelopeHeader
     */
    public $GetUnderDeliveryEnvelopeResult;
    /**
     * Constructor method for GetUnderDeliveryEnvelopeResponse
     * @uses GetUnderDeliveryEnvelopeResponse::setGetUnderDeliveryEnvelopeResult()
     * @param \ArrayType\ArrayOfEnvelopeHeader $getUnderDeliveryEnvelopeResult
     */
    public function __construct(\ArrayType\ArrayOfEnvelopeHeader $getUnderDeliveryEnvelopeResult = null)
    {
        $this
            ->setGetUnderDeliveryEnvelopeResult($getUnderDeliveryEnvelopeResult);
    }
    /**
     * Get GetUnderDeliveryEnvelopeResult value
     * @return \ArrayType\ArrayOfEnvelopeHeader|null
     */
    public function getGetUnderDeliveryEnvelopeResult()
    {
        return $this->GetUnderDeliveryEnvelopeResult;
    }
    /**
     * Set GetUnderDeliveryEnvelopeResult value
     * @param \ArrayType\ArrayOfEnvelopeHeader $getUnderDeliveryEnvelopeResult
     * @return \StructType\GetUnderDeliveryEnvelopeResponse
     */
    public function setGetUnderDeliveryEnvelopeResult(\ArrayType\ArrayOfEnvelopeHeader $getUnderDeliveryEnvelopeResult = null)
    {
        $this->GetUnderDeliveryEnvelopeResult = $getUnderDeliveryEnvelopeResult;
        return $this;
    }
}
