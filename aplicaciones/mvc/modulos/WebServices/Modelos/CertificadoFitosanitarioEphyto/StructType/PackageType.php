<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for packageType StructType
 * @subpackage Structs
 */
class PackageType extends AbstractStructBase
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
     * Constructor method for packageType
     * @uses PackageType::setActive()
     * @uses PackageType::setCode()
     * @uses PackageType::setDescription()
     * @uses PackageType::setLang()
     * @uses PackageType::setLastModified()
     * @uses PackageType::setLastModifiedBy()
     * @param bool $active
     * @param string $code
     * @param string $description
     * @param string $lang
     * @param string $lastModified
     * @param string $lastModifiedBy
     */
    public function __construct($active = null, $code = null, $description = null, $lang = null, $lastModified = null, $lastModifiedBy = null)
    {
        $this
            ->setActive($active)
            ->setCode($code)
            ->setDescription($description)
            ->setLang($lang)
            ->setLastModified($lastModified)
            ->setLastModifiedBy($lastModifiedBy);
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
     * @return \StructType\PackageType
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
     * @return \StructType\PackageType
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
     * @return \StructType\PackageType
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
     * @return \StructType\PackageType
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
     * @return \StructType\PackageType
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
     * @return \StructType\PackageType
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
}
