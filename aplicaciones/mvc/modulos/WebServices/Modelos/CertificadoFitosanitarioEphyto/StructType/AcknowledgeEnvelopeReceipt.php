<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for AcknowledgeEnvelopeReceipt StructType
 * Meta information extracted from the WSDL
 * - type: tns:AcknowledgeEnvelopeReceipt
 * @subpackage Structs
 */
class AcknowledgeEnvelopeReceipt extends AbstractStructBase
{
    /**
     * The hubTrackingNumber
     * @var string
     */
    public $hubTrackingNumber;
    /**
     * Constructor method for AcknowledgeEnvelopeReceipt
     * @uses AcknowledgeEnvelopeReceipt::setHubTrackingNumber()
     * @param string $hubTrackingNumber
     */
    public function __construct($hubTrackingNumber = null)
    {
        $this
            ->setHubTrackingNumber($hubTrackingNumber);
    }
    /**
     * Get hubTrackingNumber value
     * @return string|null
     */
    public function getHubTrackingNumber()
    {
        return $this->hubTrackingNumber;
    }
    /**
     * Set hubTrackingNumber value
     * @param string $hubTrackingNumber
     * @return \StructType\AcknowledgeEnvelopeReceipt
     */
    public function setHubTrackingNumber($hubTrackingNumber = null)
    {
        // validation for constraint: string
        if (!is_null($hubTrackingNumber) && !is_string($hubTrackingNumber)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($hubTrackingNumber, true), gettype($hubTrackingNumber)), __LINE__);
        }
        $this->hubTrackingNumber = $hubTrackingNumber;
        return $this;
    }
}
