<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for affixedSPSSeal StructType
 * @subpackage Structs
 */
class AffixedSPSSeal extends AbstractStructBase
{
    /**
     * The ID
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $ID;
    /**
     * Constructor method for affixedSPSSeal
     * @uses AffixedSPSSeal::setID()
     * @param string $iD
     */
    public function __construct($iD = null)
    {
        $this
            ->setID($iD);
    }
    /**
     * Get ID value
     * @return string|null
     */
    public function getID()
    {
        return $this->ID;
    }
    /**
     * Set ID value
     * @param string $iD
     * @return \StructType\AffixedSPSSeal
     */
    public function setID($iD = null)
    {
        // validation for constraint: string
        if (!is_null($iD) && !is_string($iD)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($iD, true), gettype($iD)), __LINE__);
        }
        $this->ID = $iD;
        return $this;
    }
}
