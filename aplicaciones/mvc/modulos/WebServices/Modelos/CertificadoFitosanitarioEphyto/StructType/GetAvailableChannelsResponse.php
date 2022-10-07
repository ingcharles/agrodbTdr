<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetAvailableChannelsResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetAvailableChannelsResponse
 * @subpackage Structs
 */
class GetAvailableChannelsResponse extends AbstractStructBase
{
    /**
     * The GetAvailableChannelsResult
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \ArrayType\ArrayOfChannels
     */
    public $GetAvailableChannelsResult;
    /**
     * Constructor method for GetAvailableChannelsResponse
     * @uses GetAvailableChannelsResponse::setGetAvailableChannelsResult()
     * @param \ArrayType\ArrayOfChannels $getAvailableChannelsResult
     */
    public function __construct(\ArrayType\ArrayOfChannels $getAvailableChannelsResult = null)
    {
        $this
            ->setGetAvailableChannelsResult($getAvailableChannelsResult);
    }
    /**
     * Get GetAvailableChannelsResult value
     * @return \ArrayType\ArrayOfChannels|null
     */
    public function getGetAvailableChannelsResult()
    {
        return $this->GetAvailableChannelsResult;
    }
    /**
     * Set GetAvailableChannelsResult value
     * @param \ArrayType\ArrayOfChannels $getAvailableChannelsResult
     * @return \StructType\GetAvailableChannelsResponse
     */
    public function setGetAvailableChannelsResult(\ArrayType\ArrayOfChannels $getAvailableChannelsResult = null)
    {
        $this->GetAvailableChannelsResult = $getAvailableChannelsResult;
        return $this;
    }
}
