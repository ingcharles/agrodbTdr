<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for includedSPSClause StructType
 * @subpackage Structs
 */
class IncludedSPSClause extends AbstractStructBase
{
    /**
     * The ID
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var int
     */
    public $ID;
    /**
     * The Content
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $Content;
    /**
     * Constructor method for includedSPSClause
     * @uses IncludedSPSClause::setID()
     * @uses IncludedSPSClause::setContent()
     * @param int $iD
     * @param string $content
     */
    public function __construct($iD = null, $content = null)
    {
        $this
            ->setID($iD)
            ->setContent($content);
    }
    /**
     * Get ID value
     * @return int|null
     */
    public function getID()
    {
        return $this->ID;
    }
    /**
     * Set ID value
     * @param int $iD
     * @return \StructType\IncludedSPSClause
     */
    public function setID($iD = null)
    {
        // validation for constraint: int
        if (!is_null($iD) && !(is_int($iD) || ctype_digit($iD))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($iD, true), gettype($iD)), __LINE__);
        }
        $this->ID = $iD;
        return $this;
    }
    /**
     * Get Content value
     * @return string|null
     */
    public function getContent()
    {
        return $this->Content;
    }
    /**
     * Set Content value
     * @param string $content
     * @return \StructType\IncludedSPSClause
     */
    public function setContent($content = null)
    {
        // validation for constraint: string
        if (!is_null($content) && !is_string($content)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($content, true), gettype($content)), __LINE__);
        }
        $this->Content = $content;
        return $this;
    }
}
