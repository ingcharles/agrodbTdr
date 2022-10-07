<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for channelRule StructType
 * @subpackage Structs
 */
class ChannelRule extends BaseEntity
{
    /**
     * The id
     * Meta information extracted from the WSDL
     * - use: required
     * @var int
     */
    public $id;
    /**
     * The active
     * Meta information extracted from the WSDL
     * - use: required
     * @var bool
     */
    public $active;
    /**
     * The certificateType
     * Meta information extracted from the WSDL
     * - use: required
     * @var int
     */
    public $certificateType;
    /**
     * The certificateStatus
     * Meta information extracted from the WSDL
     * - use: required
     * @var int
     */
    public $certificateStatus;
    /**
     * The ruleType
     * @var string
     */
    public $ruleType;
    /**
     * The countryCode
     * @var string
     */
    public $countryCode;
    /**
     * The direction
     * @var string
     */
    public $direction;
    /**
     * Constructor method for channelRule
     * @uses ChannelRule::setId()
     * @uses ChannelRule::setActive()
     * @uses ChannelRule::setCertificateType()
     * @uses ChannelRule::setCertificateStatus()
     * @uses ChannelRule::setRuleType()
     * @uses ChannelRule::setCountryCode()
     * @uses ChannelRule::setDirection()
     * @param int $id
     * @param bool $active
     * @param int $certificateType
     * @param int $certificateStatus
     * @param string $ruleType
     * @param string $countryCode
     * @param string $direction
     */
    public function __construct($id = null, $active = null, $certificateType = null, $certificateStatus = null, $ruleType = null, $countryCode = null, $direction = null)
    {
        $this
            ->setId($id)
            ->setActive($active)
            ->setCertificateType($certificateType)
            ->setCertificateStatus($certificateStatus)
            ->setRuleType($ruleType)
            ->setCountryCode($countryCode)
            ->setDirection($direction);
    }
    /**
     * Get id value
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set id value
     * @param int $id
     * @return \StructType\ChannelRule
     */
    public function setId($id = null)
    {
        // validation for constraint: int
        if (!is_null($id) && !(is_int($id) || ctype_digit($id))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($id, true), gettype($id)), __LINE__);
        }
        $this->id = $id;
        return $this;
    }
    /**
     * Get active value
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }
    /**
     * Set active value
     * @param bool $active
     * @return \StructType\ChannelRule
     */
    public function setActive($active = null)
    {
        // validation for constraint: boolean
        if (!is_null($active) && !is_bool($active)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a bool, %s given', var_export($active, true), gettype($active)), __LINE__);
        }
        $this->active = $active;
        return $this;
    }
    /**
     * Get certificateType value
     * @return int
     */
    public function getCertificateType()
    {
        return $this->certificateType;
    }
    /**
     * Set certificateType value
     * @param int $certificateType
     * @return \StructType\ChannelRule
     */
    public function setCertificateType($certificateType = null)
    {
        // validation for constraint: int
        if (!is_null($certificateType) && !(is_int($certificateType) || ctype_digit($certificateType))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($certificateType, true), gettype($certificateType)), __LINE__);
        }
        $this->certificateType = $certificateType;
        return $this;
    }
    /**
     * Get certificateStatus value
     * @return int
     */
    public function getCertificateStatus()
    {
        return $this->certificateStatus;
    }
    /**
     * Set certificateStatus value
     * @param int $certificateStatus
     * @return \StructType\ChannelRule
     */
    public function setCertificateStatus($certificateStatus = null)
    {
        // validation for constraint: int
        if (!is_null($certificateStatus) && !(is_int($certificateStatus) || ctype_digit($certificateStatus))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($certificateStatus, true), gettype($certificateStatus)), __LINE__);
        }
        $this->certificateStatus = $certificateStatus;
        return $this;
    }
    /**
     * Get ruleType value
     * @return string|null
     */
    public function getRuleType()
    {
        return $this->ruleType;
    }
    /**
     * Set ruleType value
     * @uses \EnumType\ChannelRuleType::valueIsValid()
     * @uses \EnumType\ChannelRuleType::getValidValues()
     * @throws \InvalidArgumentException
     * @param string $ruleType
     * @return \StructType\ChannelRule
     */
    public function setRuleType($ruleType = null)
    {
        // validation for constraint: enumeration
        if (!\EnumType\ChannelRuleType::valueIsValid($ruleType)) {
            throw new \InvalidArgumentException(sprintf('Invalid value(s) %s, please use one of: %s from enumeration class \EnumType\ChannelRuleType', is_array($ruleType) ? implode(', ', $ruleType) : var_export($ruleType, true), implode(', ', \EnumType\ChannelRuleType::getValidValues())), __LINE__);
        }
        $this->ruleType = $ruleType;
        return $this;
    }
    /**
     * Get countryCode value
     * @return string|null
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
    /**
     * Set countryCode value
     * @param string $countryCode
     * @return \StructType\ChannelRule
     */
    public function setCountryCode($countryCode = null)
    {
        // validation for constraint: string
        if (!is_null($countryCode) && !is_string($countryCode)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($countryCode, true), gettype($countryCode)), __LINE__);
        }
        $this->countryCode = $countryCode;
        return $this;
    }
    /**
     * Get direction value
     * @return string|null
     */
    public function getDirection()
    {
        return $this->direction;
    }
    /**
     * Set direction value
     * @uses \EnumType\ChannelDirection::valueIsValid()
     * @uses \EnumType\ChannelDirection::getValidValues()
     * @throws \InvalidArgumentException
     * @param string $direction
     * @return \StructType\ChannelRule
     */
    public function setDirection($direction = null)
    {
        // validation for constraint: enumeration
        if (!\EnumType\ChannelDirection::valueIsValid($direction)) {
            throw new \InvalidArgumentException(sprintf('Invalid value(s) %s, please use one of: %s from enumeration class \EnumType\ChannelDirection', is_array($direction) ? implode(', ', $direction) : var_export($direction, true), implode(', ', \EnumType\ChannelDirection::getValidValues())), __LINE__);
        }
        $this->direction = $direction;
        return $this;
    }
}
