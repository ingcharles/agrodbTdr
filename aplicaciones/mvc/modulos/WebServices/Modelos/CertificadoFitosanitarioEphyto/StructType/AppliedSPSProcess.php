<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for appliedSPSProcess StructType
 * @subpackage Structs
 */
class AppliedSPSProcess extends AbstractStructBase
{
    /**
     * The TypeCode
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $TypeCode;
    /**
     * The CompletionSPSPeriod
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: tns:CompletionSPSPeriod
     * @var \StructType\CompletionSPSPeriod
     */
    public $CompletionSPSPeriod;
    /**
     * The ApplicableSPSProcessCharacteristic
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: tns:ApplicableSPSProcessCharacteristic
     * @var \StructType\ApplicableSPSProcessCharacteristic
     */
    public $ApplicableSPSProcessCharacteristic;
    /**
     * Constructor method for appliedSPSProcess
     * @uses AppliedSPSProcess::setTypeCode()
     * @uses AppliedSPSProcess::setCompletionSPSPeriod()
     * @uses AppliedSPSProcess::setApplicableSPSProcessCharacteristic()
     * @param string $typeCode
     * @param \StructType\CompletionSPSPeriod $completionSPSPeriod
     * @param \StructType\ApplicableSPSProcessCharacteristic $applicableSPSProcessCharacteristic
     */
    public function __construct($typeCode = null, \StructType\CompletionSPSPeriod $completionSPSPeriod = null, \StructType\ApplicableSPSProcessCharacteristic $applicableSPSProcessCharacteristic = null)
    {
        $this
            ->setTypeCode($typeCode)
            ->setCompletionSPSPeriod($completionSPSPeriod)
            ->setApplicableSPSProcessCharacteristic($applicableSPSProcessCharacteristic);
    }
    /**
     * Get TypeCode value
     * @return string|null
     */
    public function getTypeCode()
    {
        return $this->TypeCode;
    }
    /**
     * Set TypeCode value
     * @param string $typeCode
     * @return \StructType\AppliedSPSProcess
     */
    public function setTypeCode($typeCode = null)
    {
        // validation for constraint: string
        if (!is_null($typeCode) && !is_string($typeCode)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($typeCode, true), gettype($typeCode)), __LINE__);
        }
        $this->TypeCode = $typeCode;
        return $this;
    }
    /**
     * Get CompletionSPSPeriod value
     * @return \StructType\CompletionSPSPeriod|null
     */
    public function getCompletionSPSPeriod()
    {
        return $this->CompletionSPSPeriod;
    }
    /**
     * Set CompletionSPSPeriod value
     * @param \StructType\CompletionSPSPeriod $completionSPSPeriod
     * @return \StructType\AppliedSPSProcess
     */
    public function setCompletionSPSPeriod(\StructType\CompletionSPSPeriod $completionSPSPeriod = null)
    {
        $this->CompletionSPSPeriod = $completionSPSPeriod;
        return $this;
    }
    /**
     * Get ApplicableSPSProcessCharacteristic value
     * @return \StructType\ApplicableSPSProcessCharacteristic|null
     */
    public function getApplicableSPSProcessCharacteristic()
    {
        return $this->ApplicableSPSProcessCharacteristic;
    }
    /**
     * Set ApplicableSPSProcessCharacteristic value
     * @param \StructType\ApplicableSPSProcessCharacteristic $applicableSPSProcessCharacteristic
     * @return \StructType\AppliedSPSProcess
     */
    public function setApplicableSPSProcessCharacteristic(\StructType\ApplicableSPSProcessCharacteristic $applicableSPSProcessCharacteristic = null)
    {
        $this->ApplicableSPSProcessCharacteristic = $applicableSPSProcessCharacteristic;
        return $this;
    }
}
