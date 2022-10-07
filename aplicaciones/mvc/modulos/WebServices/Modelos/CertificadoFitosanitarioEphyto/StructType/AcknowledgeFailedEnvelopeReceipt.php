<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for AcknowledgeFailedEnvelopeReceipt StructType
 * Meta information extracted from the WSDL
 * - type: tns:AcknowledgeFailedEnvelopeReceipt
 * @subpackage Structs
 */
class AcknowledgeFailedEnvelopeReceipt extends AbstractStructBase
{
    /**
     * The hubTrackingNumber
     * @var string
     */
    public $hubTrackingNumber;
    /**
     * The message
     * @var string
     */
    public $message;
    /**
     * Constructor method for AcknowledgeFailedEnvelopeReceipt
     * @uses AcknowledgeFailedEnvelopeReceipt::setHubTrackingNumber()
     * @uses AcknowledgeFailedEnvelopeReceipt::setMessage()
     * @param string $hubTrackingNumber
     * @param string $message
     */
    public function __construct($hubTrackingNumber = null, $message = null)
    {
        $this
            ->setHubTrackingNumber($hubTrackingNumber)
            ->setMessage($message);
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
     * @return \StructType\AcknowledgeFailedEnvelopeReceipt
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
     * Get message value
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }
    /**
     * Set message value
     * @param string $message
     * @return \StructType\AcknowledgeFailedEnvelopeReceipt
     */
    public function setMessage($message = null)
    {
        // validation for constraint: string
        if (!is_null($message) && !is_string($message)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($message, true), gettype($message)), __LINE__);
        }
        $this->message = $message;
        return $this;
    }
}
