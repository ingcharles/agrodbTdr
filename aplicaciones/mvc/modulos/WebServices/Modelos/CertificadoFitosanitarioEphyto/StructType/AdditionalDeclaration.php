<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for additionalDeclaration StructType
 * @subpackage Structs
 */
class AdditionalDeclaration extends AbstractStructBase
{
    /**
     * The active
     * Meta information extracted from the WSDL
     * - form: unqualified
     * @var bool
     */
    public $active;
    /**
     * The additionalCode
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $additionalCode;
    /**
     * The code
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $code;
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
     * The optionalText
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $optionalText;
    /**
     * The text
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $text;
    /**
     * Constructor method for additionalDeclaration
     * @uses AdditionalDeclaration::setActive()
     * @uses AdditionalDeclaration::setAdditionalCode()
     * @uses AdditionalDeclaration::setCode()
     * @uses AdditionalDeclaration::setLang()
     * @uses AdditionalDeclaration::setLastModified()
     * @uses AdditionalDeclaration::setLastModifiedBy()
     * @uses AdditionalDeclaration::setOptionalText()
     * @uses AdditionalDeclaration::setText()
     * @param bool $active
     * @param string $additionalCode
     * @param string $code
     * @param string $lang
     * @param string $lastModified
     * @param string $lastModifiedBy
     * @param string $optionalText
     * @param string $text
     */
    public function __construct($active = null, $additionalCode = null, $code = null, $lang = null, $lastModified = null, $lastModifiedBy = null, $optionalText = null, $text = null)
    {
        $this
            ->setActive($active)
            ->setAdditionalCode($additionalCode)
            ->setCode($code)
            ->setLang($lang)
            ->setLastModified($lastModified)
            ->setLastModifiedBy($lastModifiedBy)
            ->setOptionalText($optionalText)
            ->setText($text);
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
     * @return \StructType\AdditionalDeclaration
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
     * Get additionalCode value
     * @return string|null
     */
    public function getAdditionalCode()
    {
        return $this->additionalCode;
    }
    /**
     * Set additionalCode value
     * @param string $additionalCode
     * @return \StructType\AdditionalDeclaration
     */
    public function setAdditionalCode($additionalCode = null)
    {
        // validation for constraint: string
        if (!is_null($additionalCode) && !is_string($additionalCode)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($additionalCode, true), gettype($additionalCode)), __LINE__);
        }
        $this->additionalCode = $additionalCode;
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
     * @return \StructType\AdditionalDeclaration
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
     * @return \StructType\AdditionalDeclaration
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
     * @return \StructType\AdditionalDeclaration
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
     * @return \StructType\AdditionalDeclaration
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
     * Get optionalText value
     * @return string|null
     */
    public function getOptionalText()
    {
        return $this->optionalText;
    }
    /**
     * Set optionalText value
     * @param string $optionalText
     * @return \StructType\AdditionalDeclaration
     */
    public function setOptionalText($optionalText = null)
    {
        // validation for constraint: string
        if (!is_null($optionalText) && !is_string($optionalText)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($optionalText, true), gettype($optionalText)), __LINE__);
        }
        $this->optionalText = $optionalText;
        return $this;
    }
    /**
     * Get text value
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }
    /**
     * Set text value
     * @param string $text
     * @return \StructType\AdditionalDeclaration
     */
    public function setText($text = null)
    {
        // validation for constraint: string
        if (!is_null($text) && !is_string($text)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($text, true), gettype($text)), __LINE__);
        }
        $this->text = $text;
        return $this;
    }
}
