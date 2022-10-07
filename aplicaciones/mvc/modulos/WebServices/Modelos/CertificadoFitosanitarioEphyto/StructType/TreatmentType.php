<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for treatmentType StructType
 * @subpackage Structs
 */
class TreatmentType extends AbstractStructBase
{
    /**
     * The active
     * Meta information extracted from the WSDL
     * - form: unqualified
     * @var bool
     */
    public $active;
    /**
     * The code
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $code;
    /**
     * The description
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $description;
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
     * The level
     * Meta information extracted from the WSDL
     * - form: unqualified
     * @var int
     */
    public $level;
    /**
     * The parentCode
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $parentCode;
    /**
     * Constructor method for treatmentType
     * @uses TreatmentType::setActive()
     * @uses TreatmentType::setCode()
     * @uses TreatmentType::setDescription()
     * @uses TreatmentType::setLang()
     * @uses TreatmentType::setLastModified()
     * @uses TreatmentType::setLastModifiedBy()
     * @uses TreatmentType::setLevel()
     * @uses TreatmentType::setParentCode()
     * @param bool $active
     * @param string $code
     * @param string $description
     * @param string $lang
     * @param string $lastModified
     * @param string $lastModifiedBy
     * @param int $level
     * @param string $parentCode
     */
    public function __construct($active = null, $code = null, $description = null, $lang = null, $lastModified = null, $lastModifiedBy = null, $level = null, $parentCode = null)
    {
        $this
            ->setActive($active)
            ->setCode($code)
            ->setDescription($description)
            ->setLang($lang)
            ->setLastModified($lastModified)
            ->setLastModifiedBy($lastModifiedBy)
            ->setLevel($level)
            ->setParentCode($parentCode);
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
     * @return \StructType\TreatmentType
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
     * Get code value
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }
    /**
     * Set code value
     * @param string $code
     * @return \StructType\TreatmentType
     */
    public function setCode($code = null)
    {
        // validation for constraint: string
        if (!is_null($code) && !is_string($code)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($code, true), gettype($code)), __LINE__);
        }
        $this->code = $code;
        return $this;
    }
    /**
     * Get description value
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Set description value
     * @param string $description
     * @return \StructType\TreatmentType
     */
    public function setDescription($description = null)
    {
        // validation for constraint: string
        if (!is_null($description) && !is_string($description)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($description, true), gettype($description)), __LINE__);
        }
        $this->description = $description;
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
     * @return \StructType\TreatmentType
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
     * @return \StructType\TreatmentType
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
     * @return \StructType\TreatmentType
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
     * Get level value
     * @return int|null
     */
    public function getLevel()
    {
        return $this->level;
    }
    /**
     * Set level value
     * @param int $level
     * @return \StructType\TreatmentType
     */
    public function setLevel($level = null)
    {
        // validation for constraint: int
        if (!is_null($level) && !(is_int($level) || ctype_digit($level))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($level, true), gettype($level)), __LINE__);
        }
        $this->level = $level;
        return $this;
    }
    /**
     * Get parentCode value
     * @return string|null
     */
    public function getParentCode()
    {
        return $this->parentCode;
    }
    /**
     * Set parentCode value
     * @param string $parentCode
     * @return \StructType\TreatmentType
     */
    public function setParentCode($parentCode = null)
    {
        // validation for constraint: string
        if (!is_null($parentCode) && !is_string($parentCode)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($parentCode, true), gettype($parentCode)), __LINE__);
        }
        $this->parentCode = $parentCode;
        return $this;
    }
}
