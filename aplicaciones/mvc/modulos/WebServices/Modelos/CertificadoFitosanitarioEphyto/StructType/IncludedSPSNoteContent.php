<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for includedSPSNoteContent StructType
 * @subpackage Structs
 */
class IncludedSPSNoteContent extends AbstractStructBase
{
    /**
     * The _
     * @var string
     */
    public $_;
    /**
     * The languageID
     * @var string
     */
    public $languageID;
    /**
     * Constructor method for includedSPSNoteContent
     * @uses IncludedSPSNoteContent::set_()
     * @uses IncludedSPSNoteContent::setLanguageID()
     * @param string $_
     * @param string $languageID
     */
    public function __construct($_ = null, $languageID = null)
    {
        $this
            ->set_($_)
            ->setLanguageID($languageID);
    }
    /**
     * Get _ value
     * @return string|null
     */
    public function get_()
    {
        return $this->_;
    }
    /**
     * Set _ value
     * @param string $_
     * @return \StructType\IncludedSPSNoteContent
     */
    public function set_($_ = null)
    {
        // validation for constraint: string
        if (!is_null($_) && !is_string($_)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($_, true), gettype($_)), __LINE__);
        }
        $this->_ = $_;
        return $this;
    }
    /**
     * Get languageID value
     * @return string|null
     */
    public function getLanguageID()
    {
        return $this->languageID;
    }
    /**
     * Set languageID value
     * @param string $languageID
     * @return \StructType\IncludedSPSNoteContent
     */
    public function setLanguageID($languageID = null)
    {
        // validation for constraint: string
        if (!is_null($languageID) && !is_string($languageID)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($languageID, true), gettype($languageID)), __LINE__);
        }
        $this->languageID = $languageID;
        return $this;
    }
}
