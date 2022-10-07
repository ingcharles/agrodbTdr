<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetProfileResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetProfileResponse
 * @subpackage Structs
 */
class GetProfileResponse extends AbstractStructBase
{
    /**
     * The GetProfileResult
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \StructType\Nppo
     */
    public $GetProfileResult;
    /**
     * Constructor method for GetProfileResponse
     * @uses GetProfileResponse::setGetProfileResult()
     * @param \StructType\Nppo $getProfileResult
     */
    public function __construct(\StructType\Nppo $getProfileResult = null)
    {
        $this
            ->setGetProfileResult($getProfileResult);
    }
    /**
     * Get GetProfileResult value
     * @return \StructType\Nppo|null
     */
    public function getGetProfileResult()
    {
        return $this->GetProfileResult;
    }
    /**
     * Set GetProfileResult value
     * @param \StructType\Nppo $getProfileResult
     * @return \StructType\GetProfileResponse
     */
    public function setGetProfileResult(\StructType\Nppo $getProfileResult = null)
    {
        $this->GetProfileResult = $getProfileResult;
        return $this;
    }
}
