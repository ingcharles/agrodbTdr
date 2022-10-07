<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetActiveNpposResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetActiveNpposResponse
 * @subpackage Structs
 */
class GetActiveNpposResponse extends AbstractStructBase
{
    /**
     * The GetActiveNpposResult
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \ArrayType\ArrayOfNppo
     */
    public $GetActiveNpposResult;
    /**
     * Constructor method for GetActiveNpposResponse
     * @uses GetActiveNpposResponse::setGetActiveNpposResult()
     * @param \ArrayType\ArrayOfNppo $getActiveNpposResult
     */
    public function __construct(\ArrayType\ArrayOfNppo $getActiveNpposResult = null)
    {
        $this
            ->setGetActiveNpposResult($getActiveNpposResult);
    }
    /**
     * Get GetActiveNpposResult value
     * @return \ArrayType\ArrayOfNppo|null
     */
    public function getGetActiveNpposResult()
    {
        return $this->GetActiveNpposResult;
    }
    /**
     * Set GetActiveNpposResult value
     * @param \ArrayType\ArrayOfNppo $getActiveNpposResult
     * @return \StructType\GetActiveNpposResponse
     */
    public function setGetActiveNpposResult(\ArrayType\ArrayOfNppo $getActiveNpposResult = null)
    {
        $this->GetActiveNpposResult = $getActiveNpposResult;
        return $this;
    }
}
