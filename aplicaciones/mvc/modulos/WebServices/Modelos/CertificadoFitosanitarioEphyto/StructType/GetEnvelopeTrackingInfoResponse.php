<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetEnvelopeTrackingInfoResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetEnvelopeTrackingInfoResponse
 * @subpackage Structs
 */
class GetEnvelopeTrackingInfoResponse extends AbstractStructBase
{
    /**
     * The GetEnvelopeTrackingInfoResult
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \StructType\EnvelopeHeader
     */
    public $GetEnvelopeTrackingInfoResult;
    /**
     * Constructor method for GetEnvelopeTrackingInfoResponse
     * @uses GetEnvelopeTrackingInfoResponse::setGetEnvelopeTrackingInfoResult()
     * @param \StructType\EnvelopeHeader $getEnvelopeTrackingInfoResult
     */
    public function __construct(\StructType\EnvelopeHeader $getEnvelopeTrackingInfoResult = null)
    {
        $this
            ->setGetEnvelopeTrackingInfoResult($getEnvelopeTrackingInfoResult);
    }
    /**
     * Get GetEnvelopeTrackingInfoResult value
     * @return \StructType\EnvelopeHeader|null
     */
    public function getGetEnvelopeTrackingInfoResult()
    {
        return $this->GetEnvelopeTrackingInfoResult;
    }
    /**
     * Set GetEnvelopeTrackingInfoResult value
     * @param \StructType\EnvelopeHeader $getEnvelopeTrackingInfoResult
     * @return \StructType\GetEnvelopeTrackingInfoResponse
     */
    public function setGetEnvelopeTrackingInfoResult(\StructType\EnvelopeHeader $getEnvelopeTrackingInfoResult = null)
    {
        $this->GetEnvelopeTrackingInfoResult = $getEnvelopeTrackingInfoResult;
        return $this;
    }
}
