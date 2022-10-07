<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for mainCarriageSPSTransportMovement StructType
 * @subpackage Structs
 */
class MainCarriageSPSTransportMovement extends AbstractStructBase
{
    /**
     * The ID
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $ID;
    /**
     * The ModeCode
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var int
     */
    public $ModeCode;
    /**
     * The UsedSPSTransportMeans
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: tns:UsedSPSTransportMeans
     * @var \StructType\UsedSPSTransportMeans
     */
    public $UsedSPSTransportMeans;
    /**
     * Constructor method for mainCarriageSPSTransportMovement
     * @uses MainCarriageSPSTransportMovement::setID()
     * @uses MainCarriageSPSTransportMovement::setModeCode()
     * @uses MainCarriageSPSTransportMovement::setUsedSPSTransportMeans()
     * @param string $iD
     * @param int $modeCode
     * @param \StructType\UsedSPSTransportMeans $usedSPSTransportMeans
     */
    public function __construct($iD = null, $modeCode = null, \StructType\UsedSPSTransportMeans $usedSPSTransportMeans = null)
    {
        $this
            ->setID($iD)
            ->setModeCode($modeCode)
            ->setUsedSPSTransportMeans($usedSPSTransportMeans);
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
     * @return \StructType\MainCarriageSPSTransportMovement
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
    /**
     * Get ModeCode value
     * @return int|null
     */
    public function getModeCode()
    {
        return $this->ModeCode;
    }
    /**
     * Set ModeCode value
     * @param int $modeCode
     * @return \StructType\MainCarriageSPSTransportMovement
     */
    public function setModeCode($modeCode = null)
    {
        // validation for constraint: int
        if (!is_null($modeCode) && !(is_int($modeCode) || ctype_digit($modeCode))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($modeCode, true), gettype($modeCode)), __LINE__);
        }
        $this->ModeCode = $modeCode;
        return $this;
    }
    /**
     * Get UsedSPSTransportMeans value
     * @return \StructType\UsedSPSTransportMeans|null
     */
    public function getUsedSPSTransportMeans()
    {
        return $this->UsedSPSTransportMeans;
    }
    /**
     * Set UsedSPSTransportMeans value
     * @param \StructType\UsedSPSTransportMeans $usedSPSTransportMeans
     * @return \StructType\MainCarriageSPSTransportMovement
     */
    public function setUsedSPSTransportMeans(\StructType\UsedSPSTransportMeans $usedSPSTransportMeans = null)
    {
        $this->UsedSPSTransportMeans = $usedSPSTransportMeans;
        return $this;
    }
}
