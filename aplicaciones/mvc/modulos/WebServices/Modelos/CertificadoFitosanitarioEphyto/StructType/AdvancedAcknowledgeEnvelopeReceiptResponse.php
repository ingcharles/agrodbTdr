<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for AdvancedAcknowledgeEnvelopeReceiptResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:AdvancedAcknowledgeEnvelopeReceiptResponse
 * @subpackage Structs
 */
class AdvancedAcknowledgeEnvelopeReceiptResponse extends AbstractStructBase
{
    /**
     * The return
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var string
     */
    public $return;
    /**
     * Constructor method for AdvancedAcknowledgeEnvelopeReceiptResponse
     * @uses AdvancedAcknowledgeEnvelopeReceiptResponse::setReturn()
     * @param string $return
     */
    public function __construct($return = null)
    {
        $this
            ->setReturn($return);
    }
    /**
     * Get return value
     * @return string|null
     */
    public function getReturn()
    {
        return $this->return;
    }
    /**
     * Set return value
     * @param string $return
     * @return \StructType\AdvancedAcknowledgeEnvelopeReceiptResponse
     */
    public function setReturn($return = null)
    {
        // validation for constraint: string
        if (!is_null($return) && !is_string($return)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($return, true), gettype($return)), __LINE__);
        }
        $this->return = $return;
        return $this;
    }
}
