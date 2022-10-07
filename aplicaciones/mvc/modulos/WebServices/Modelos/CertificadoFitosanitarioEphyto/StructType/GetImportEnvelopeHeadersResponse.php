<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetImportEnvelopeHeadersResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetImportEnvelopeHeadersResponse
 * @subpackage Structs
 */
class GetImportEnvelopeHeadersResponse extends AbstractStructBase
{
    /**
     * The GetImportEnvelopeHeadersResult
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \ArrayType\ArrayOfEnvelopeHeader
     */
    public $GetImportEnvelopeHeadersResult;
    /**
     * Constructor method for GetImportEnvelopeHeadersResponse
     * @uses GetImportEnvelopeHeadersResponse::setGetImportEnvelopeHeadersResult()
     * @param \ArrayType\ArrayOfEnvelopeHeader $getImportEnvelopeHeadersResult
     */
    public function __construct(\ArrayType\ArrayOfEnvelopeHeader $getImportEnvelopeHeadersResult = null)
    {
        $this
            ->setGetImportEnvelopeHeadersResult($getImportEnvelopeHeadersResult);
    }
    /**
     * Get GetImportEnvelopeHeadersResult value
     * @return \ArrayType\ArrayOfEnvelopeHeader|null
     */
    public function getGetImportEnvelopeHeadersResult()
    {
        return $this->GetImportEnvelopeHeadersResult;
    }
    /**
     * Set GetImportEnvelopeHeadersResult value
     * @param \ArrayType\ArrayOfEnvelopeHeader $getImportEnvelopeHeadersResult
     * @return \StructType\GetImportEnvelopeHeadersResponse
     */
    public function setGetImportEnvelopeHeadersResult(\ArrayType\ArrayOfEnvelopeHeader $getImportEnvelopeHeadersResult = null)
    {
        $this->GetImportEnvelopeHeadersResult = $getImportEnvelopeHeadersResult;
        return $this;
    }
}
