<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for statement StructType
 * @subpackage Structs
 */
class Statement extends AbstractStructBase
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
     * The docTypeOnly
     * Meta information extracted from the WSDL
     * - form: unqualified
     * @var int
     */
    public $docTypeOnly;
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
     * The text
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $text;
    /**
     * Constructor method for statement
     * @uses Statement::setActive()
     * @uses Statement::setCode()
     * @uses Statement::setDocTypeOnly()
     * @uses Statement::setLang()
     * @uses Statement::setLastModified()
     * @uses Statement::setLastModifiedBy()
     * @uses Statement::setText()
     * @param bool $active
     * @param string $code
     * @param int $docTypeOnly
     * @param string $lang
     * @param string $lastModified
     * @param string $lastModifiedBy
     * @param string $text
     */
    public function __construct($active = null, $code = null, $docTypeOnly = null, $lang = null, $lastModified = null, $lastModifiedBy = null, $text = null)
    {
        $this
            ->setActive($active)
            ->setCode($code)
            ->setDocTypeOnly($docTypeOnly)
            ->setLang($lang)
            ->setLastModified($lastModified)
            ->setLastModifiedBy($lastModifiedBy)
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
     * @return \StructType\Statement
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
     * @return \StructType\Statement
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
     * Get docTypeOnly value
     * @return int|null
     */
    public function getDocTypeOnly()
    {
        return $this->docTypeOnly;
    }
    /**
     * Set docTypeOnly value
     * @param int $docTypeOnly
     * @return \StructType\Statement
     */
    public function setDocTypeOnly($docTypeOnly = null)
    {
        // validation for constraint: int
        if (!is_null($docTypeOnly) && !(is_int($docTypeOnly) || ctype_digit($docTypeOnly))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($docTypeOnly, true), gettype($docTypeOnly)), __LINE__);
        }
        $this->docTypeOnly = $docTypeOnly;
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
     * @return \StructType\Statement
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
     * @return \StructType\Statement
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
     * @return \StructType\Statement
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
     * @return \StructType\Statement
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
