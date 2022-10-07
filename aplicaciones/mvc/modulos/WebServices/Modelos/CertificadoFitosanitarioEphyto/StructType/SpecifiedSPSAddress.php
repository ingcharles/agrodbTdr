<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for specifiedSPSAddress StructType
 * @subpackage Structs
 */
class SpecifiedSPSAddress extends AbstractStructBase
{
    /**
     * The LineOne
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:LineOne
     * @var string
     */
    public $LineOne;
    /**
     * The LineTwo
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:LineTwo
     * @var string
     */
    public $LineTwo;
    /**
     * The LineThree
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:LineThree
     * @var string
     */
    public $LineThree;
    /**
     * The LineFour
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:LineFour
     * @var string
     */
    public $LineFour;
    /**
     * The LineFive
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:LineFive
     * @var string
     */
    public $LineFive;
    /**
     * Constructor method for specifiedSPSAddress
     * @uses SpecifiedSPSAddress::setLineOne()
     * @uses SpecifiedSPSAddress::setLineTwo()
     * @uses SpecifiedSPSAddress::setLineThree()
     * @uses SpecifiedSPSAddress::setLineFour()
     * @uses SpecifiedSPSAddress::setLineFive()
     * @param string $lineOne
     * @param string $lineTwo
     * @param string $lineThree
     * @param string $lineFour
     * @param string $lineFive
     */
    public function __construct($lineOne = null, $lineTwo = null, $lineThree = null, $lineFour = null, $lineFive = null)
    {
        $this
            ->setLineOne($lineOne)
            ->setLineTwo($lineTwo)
            ->setLineThree($lineThree)
            ->setLineFour($lineFour)
            ->setLineFive($lineFive);
    }
    /**
     * Get LineOne value
     * @return string|null
     */
    public function getLineOne()
    {
        return $this->LineOne;
    }
    /**
     * Set LineOne value
     * @param string $lineOne
     * @return \StructType\SpecifiedSPSAddress
     */
    public function setLineOne($lineOne = null)
    {
        // validation for constraint: string
        if (!is_null($lineOne) && !is_string($lineOne)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lineOne, true), gettype($lineOne)), __LINE__);
        }
        $this->LineOne = $lineOne;
        return $this;
    }
    /**
     * Get LineTwo value
     * @return string|null
     */
    public function getLineTwo()
    {
        return $this->LineTwo;
    }
    /**
     * Set LineTwo value
     * @param string $lineTwo
     * @return \StructType\SpecifiedSPSAddress
     */
    public function setLineTwo($lineTwo = null)
    {
        // validation for constraint: string
        if (!is_null($lineTwo) && !is_string($lineTwo)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lineTwo, true), gettype($lineTwo)), __LINE__);
        }
        $this->LineTwo = $lineTwo;
        return $this;
    }
    /**
     * Get LineThree value
     * @return string|null
     */
    public function getLineThree()
    {
        return $this->LineThree;
    }
    /**
     * Set LineThree value
     * @param string $lineThree
     * @return \StructType\SpecifiedSPSAddress
     */
    public function setLineThree($lineThree = null)
    {
        // validation for constraint: string
        if (!is_null($lineThree) && !is_string($lineThree)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lineThree, true), gettype($lineThree)), __LINE__);
        }
        $this->LineThree = $lineThree;
        return $this;
    }
    /**
     * Get LineFour value
     * @return string|null
     */
    public function getLineFour()
    {
        return $this->LineFour;
    }
    /**
     * Set LineFour value
     * @param string $lineFour
     * @return \StructType\SpecifiedSPSAddress
     */
    public function setLineFour($lineFour = null)
    {
        // validation for constraint: string
        if (!is_null($lineFour) && !is_string($lineFour)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lineFour, true), gettype($lineFour)), __LINE__);
        }
        $this->LineFour = $lineFour;
        return $this;
    }
    /**
     * Get LineFive value
     * @return string|null
     */
    public function getLineFive()
    {
        return $this->LineFive;
    }
    /**
     * Set LineFive value
     * @param string $lineFive
     * @return \StructType\SpecifiedSPSAddress
     */
    public function setLineFive($lineFive = null)
    {
        // validation for constraint: string
        if (!is_null($lineFive) && !is_string($lineFive)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lineFive, true), gettype($lineFive)), __LINE__);
        }
        $this->LineFive = $lineFive;
        return $this;
    }
}
