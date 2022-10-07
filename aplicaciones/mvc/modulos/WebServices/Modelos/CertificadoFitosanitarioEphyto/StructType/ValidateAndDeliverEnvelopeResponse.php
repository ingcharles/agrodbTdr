<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ValidateAndDeliverEnvelopeResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:ValidateAndDeliverEnvelopeResponse
 * @subpackage Structs
 */
class ValidateAndDeliverEnvelopeResponse extends AbstractStructBase
{
    /**
     * The ValidateAndDeliverEnvelopeResult
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \StructType\EnvelopeHeader
     */
    public $ValidateAndDeliverEnvelopeResult;
    /**
     * Constructor method for ValidateAndDeliverEnvelopeResponse
     * @uses ValidateAndDeliverEnvelopeResponse::setValidateAndDeliverEnvelopeResult()
     * @param \StructType\EnvelopeHeader $validateAndDeliverEnvelopeResult
     */
    public function __construct(\StructType\EnvelopeHeader $validateAndDeliverEnvelopeResult = null)
    {
        $this
            ->setValidateAndDeliverEnvelopeResult($validateAndDeliverEnvelopeResult);
    }
    /**
     * Get ValidateAndDeliverEnvelopeResult value
     * @return \StructType\EnvelopeHeader|null
     */
    public function getValidateAndDeliverEnvelopeResult()
    {
        return $this->ValidateAndDeliverEnvelopeResult;
    }
    /**
     * Set ValidateAndDeliverEnvelopeResult value
     * @param \StructType\EnvelopeHeader $validateAndDeliverEnvelopeResult
     * @return \StructType\ValidateAndDeliverEnvelopeResponse
     */
    public function setValidateAndDeliverEnvelopeResult(\StructType\EnvelopeHeader $validateAndDeliverEnvelopeResult = null)
    {
        $this->ValidateAndDeliverEnvelopeResult = $validateAndDeliverEnvelopeResult;
        return $this;
    }
}
