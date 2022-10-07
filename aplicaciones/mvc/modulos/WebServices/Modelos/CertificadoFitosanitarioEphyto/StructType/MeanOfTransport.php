<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for meanOfTransport StructType
 * @subpackage Structs
 */
class MeanOfTransport extends BaseEntity
{
    /**
     * The active
     * Meta information extracted from the WSDL
     * - form: unqualified
     * @var bool
     */
    public $active;
    /**
     * The lang
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $lang;
    /**
     * The lastModified
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $lastModified;
    /**
     * The lastModifiedBy
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $lastModifiedBy;
    /**
     * The modeCode
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var int
     */
    public $modeCode;
    /**
     * The usedTransportMean
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $usedTransportMean;
    /**
     * Constructor method for meanOfTransport
     * @uses MeanOfTransport::setActive()
     * @uses MeanOfTransport::setLang()
     * @uses MeanOfTransport::setLastModified()
     * @uses MeanOfTransport::setLastModifiedBy()
     * @uses MeanOfTransport::setModeCode()
     * @uses MeanOfTransport::setUsedTransportMean()
     * @param bool $active
     * @param string $lang
     * @param string $lastModified
     * @param string $lastModifiedBy
     * @param int $modeCode
     * @param string $usedTransportMean
     */
    public function __construct($active = null, $lang = null, $lastModified = null, $lastModifiedBy = null, $modeCode = null, $usedTransportMean = null)
    {
        $this
            ->setActive($active)
            ->setLang($lang)
            ->setLastModified($lastModified)
            ->setLastModifiedBy($lastModifiedBy)
            ->setModeCode($modeCode)
            ->setUsedTransportMean($usedTransportMean);
    }
    /**
     * Get active value
     * @return bool|null
     */
    public function getActive()
    {
        return $this->active;
    }
    /**
     * Set active value
     * @param bool $active
     * @return \StructType\MeanOfTransport
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
     * Get lang value
     * @return string|null
     */
    public function getLang()
    {
        return $this->lang;
    }
    /**
     * Set lang value
     * @param string $lang
     * @return \StructType\MeanOfTransport
     */
    public function setLang($lang = null)
    {
        // validation for constraint: string
        if (!is_null($lang) && !is_string($lang)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lang, true), gettype($lang)), __LINE__);
        }
        $this->lang = $lang;
        return $this;
    }
    /**
     * Get lastModified value
     * @return string|null
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }
    /**
     * Set lastModified value
     * @param string $lastModified
     * @return \StructType\MeanOfTransport
     */
    public function setLastModified($lastModified = null)
    {
        // validation for constraint: string
        if (!is_null($lastModified) && !is_string($lastModified)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lastModified, true), gettype($lastModified)), __LINE__);
        }
        $this->lastModified = $lastModified;
        return $this;
    }
    /**
     * Get lastModifiedBy value
     * @return string|null
     */
    public function getLastModifiedBy()
    {
        return $this->lastModifiedBy;
    }
    /**
     * Set lastModifiedBy value
     * @param string $lastModifiedBy
     * @return \StructType\MeanOfTransport
     */
    public function setLastModifiedBy($lastModifiedBy = null)
    {
        // validation for constraint: string
        if (!is_null($lastModifiedBy) && !is_string($lastModifiedBy)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lastModifiedBy, true), gettype($lastModifiedBy)), __LINE__);
        }
        $this->lastModifiedBy = $lastModifiedBy;
        return $this;
    }
    /**
     * Get modeCode value
     * @return int|null
     */
    public function getModeCode()
    {
        return $this->modeCode;
    }
    /**
     * Set modeCode value
     * @param int $modeCode
     * @return \StructType\MeanOfTransport
     */
    public function setModeCode($modeCode = null)
    {
        // validation for constraint: int
        if (!is_null($modeCode) && !(is_int($modeCode) || ctype_digit($modeCode))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($modeCode, true), gettype($modeCode)), __LINE__);
        }
        $this->modeCode = $modeCode;
        return $this;
    }
    /**
     * Get usedTransportMean value
     * @return string|null
     */
    public function getUsedTransportMean()
    {
        return $this->usedTransportMean;
    }
    /**
     * Set usedTransportMean value
     * @param string $usedTransportMean
     * @return \StructType\MeanOfTransport
     */
    public function setUsedTransportMean($usedTransportMean = null)
    {
        // validation for constraint: string
        if (!is_null($usedTransportMean) && !is_string($usedTransportMean)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($usedTransportMean, true), gettype($usedTransportMean)), __LINE__);
        }
        $this->usedTransportMean = $usedTransportMean;
        return $this;
    }
}
