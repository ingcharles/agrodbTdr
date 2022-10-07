<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PULLSingleImportEnvelopeResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:PULLSingleImportEnvelopeResponse
 * @subpackage Structs
 */
class PULLSingleImportEnvelopeResponse extends AbstractStructBase
{
    /**
     * The PULLSingleImportEnvelopeResult
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \StructType\Envelope
     */
    public $PULLSingleImportEnvelopeResult;
    /**
     * Constructor method for PULLSingleImportEnvelopeResponse
     * @uses PULLSingleImportEnvelopeResponse::setPULLSingleImportEnvelopeResult()
     * @param \StructType\Envelope $pULLSingleImportEnvelopeResult
     */
    public function __construct(\StructType\Envelope $pULLSingleImportEnvelopeResult = null)
    {
        $this
            ->setPULLSingleImportEnvelopeResult($pULLSingleImportEnvelopeResult);
    }
    /**
     * Get PULLSingleImportEnvelopeResult value
     * @return \StructType\Envelope|null
     */
    public function getPULLSingleImportEnvelopeResult()
    {
        return $this->PULLSingleImportEnvelopeResult;
    }
    /**
     * Set PULLSingleImportEnvelopeResult value
     * @param \StructType\Envelope $pULLSingleImportEnvelopeResult
     * @return \StructType\PULLSingleImportEnvelopeResponse
     */
    public function setPULLSingleImportEnvelopeResult(\StructType\Envelope $pULLSingleImportEnvelopeResult = null)
    {
        $this->PULLSingleImportEnvelopeResult = $pULLSingleImportEnvelopeResult;
        return $this;
    }
}
