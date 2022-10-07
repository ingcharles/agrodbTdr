<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PULLImportEnvelopeResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:PULLImportEnvelopeResponse
 * @subpackage Structs
 */
class PULLImportEnvelopeResponse extends AbstractStructBase
{
    /**
     * The PULLImportEnvelopesResult
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \ArrayType\ArrayOfEnvelope
     */
    public $PULLImportEnvelopesResult;
    /**
     * Constructor method for PULLImportEnvelopeResponse
     * @uses PULLImportEnvelopeResponse::setPULLImportEnvelopesResult()
     * @param \ArrayType\ArrayOfEnvelope $pULLImportEnvelopesResult
     */
    public function __construct(\ArrayType\ArrayOfEnvelope $pULLImportEnvelopesResult = null)
    {
        $this
            ->setPULLImportEnvelopesResult($pULLImportEnvelopesResult);
    }
    /**
     * Get PULLImportEnvelopesResult value
     * @return \ArrayType\ArrayOfEnvelope|null
     */
    public function getPULLImportEnvelopesResult()
    {
        return $this->PULLImportEnvelopesResult;
    }
    /**
     * Set PULLImportEnvelopesResult value
     * @param \ArrayType\ArrayOfEnvelope $pULLImportEnvelopesResult
     * @return \StructType\PULLImportEnvelopeResponse
     */
    public function setPULLImportEnvelopesResult(\ArrayType\ArrayOfEnvelope $pULLImportEnvelopesResult = null)
    {
        $this->PULLImportEnvelopesResult = $pULLImportEnvelopesResult;
        return $this;
    }
}
