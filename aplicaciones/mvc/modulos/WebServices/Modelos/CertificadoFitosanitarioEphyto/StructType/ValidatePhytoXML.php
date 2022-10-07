<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ValidatePhytoXML StructType
 * Meta information extracted from the WSDL
 * - type: tns:ValidatePhytoXML
 * @subpackage Structs
 */
class ValidatePhytoXML extends AbstractStructBase
{
    /**
     * The ePhytoXML
     * @var string
     */
    public $ePhytoXML;
    /**
     * Constructor method for ValidatePhytoXML
     * @uses ValidatePhytoXML::setEPhytoXML()
     * @param string $ePhytoXML
     */
    public function __construct($ePhytoXML = null)
    {
        $this
            ->setEPhytoXML($ePhytoXML);
    }
    /**
     * Get ePhytoXML value
     * @return string|null
     */
    public function getEPhytoXML()
    {
        return $this->ePhytoXML;
    }
    /**
     * Set ePhytoXML value
     * @param string $ePhytoXML
     * @return \StructType\ValidatePhytoXML
     */
    public function setEPhytoXML($ePhytoXML = null)
    {
        // validation for constraint: string
        if (!is_null($ePhytoXML) && !is_string($ePhytoXML)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($ePhytoXML, true), gettype($ePhytoXML)), __LINE__);
        }
        $this->ePhytoXML = $ePhytoXML;
        return $this;
    }
}
