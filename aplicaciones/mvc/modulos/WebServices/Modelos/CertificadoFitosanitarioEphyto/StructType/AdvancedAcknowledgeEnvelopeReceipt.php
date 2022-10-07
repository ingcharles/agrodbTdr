<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for AdvancedAcknowledgeEnvelopeReceipt StructType
 * Meta information extracted from the WSDL
 * - type: tns:AdvancedAcknowledgeEnvelopeReceipt
 * @subpackage Structs
 */
class AdvancedAcknowledgeEnvelopeReceipt extends AbstractStructBase
{
    /**
     * The hubTrackingNumber
     * @var string
     */
    public $hubTrackingNumber;
    /**
     * The warningMessage
     * @var string
     */
    public $warningMessage;
    /**
     * Constructor method for AdvancedAcknowledgeEnvelopeReceipt
     * @uses AdvancedAcknowledgeEnvelopeReceipt::setHubTrackingNumber()
     * @uses AdvancedAcknowledgeEnvelopeReceipt::setWarningMessage()
     * @param string $hubTrackingNumber
     * @param string $warningMessage
     */
    public function __construct($hubTrackingNumber = null, $warningMessage = null)
    {
        $this
            ->setHubTrackingNumber($hubTrackingNumber)
            ->setWarningMessage($warningMessage);
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
     * @return \StructType\AdvancedAcknowledgeEnvelopeReceipt
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
    /**
     * Get warningMessage value
     * @return string|null
     */
    public function getWarningMessage()
    {
        return $this->warningMessage;
    }
    /**
     * Set warningMessage value
     * @param string $warningMessage
     * @return \StructType\AdvancedAcknowledgeEnvelopeReceipt
     */
    public function setWarningMessage($warningMessage = null)
    {
        // validation for constraint: string
        if (!is_null($warningMessage) && !is_string($warningMessage)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($warningMessage, true), gettype($warningMessage)), __LINE__);
        }
        $this->warningMessage = $warningMessage;
        return $this;
    }
}
