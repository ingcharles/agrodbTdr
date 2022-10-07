<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetMeanOfTransportsResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetMeanOfTransportsResponse
 * @subpackage Structs
 */
class GetMeanOfTransportsResponse extends AbstractStructBase
{
    /**
     * The MeanOfTransport
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\MeanOfTransport[]
     */
    public $MeanOfTransport;
    /**
     * Constructor method for GetMeanOfTransportsResponse
     * @uses GetMeanOfTransportsResponse::setMeanOfTransport()
     * @param \StructType\MeanOfTransport[] $meanOfTransport
     */
    public function __construct(array $meanOfTransport = array())
    {
        $this
            ->setMeanOfTransport($meanOfTransport);
    }
    /**
     * Get MeanOfTransport value
     * @return \StructType\MeanOfTransport[]|null
     */
    public function getMeanOfTransport()
    {
        return $this->MeanOfTransport;
    }
    /**
     * This method is responsible for validating the values passed to the setMeanOfTransport method
     * This method is willingly generated in order to preserve the one-line inline validation within the setMeanOfTransport method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateMeanOfTransportForArrayConstraintsFromSetMeanOfTransport(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $getMeanOfTransportsResponseMeanOfTransportItem) {
            // validation for constraint: itemType
            if (!$getMeanOfTransportsResponseMeanOfTransportItem instanceof \StructType\MeanOfTransport) {
                $invalidValues[] = is_object($getMeanOfTransportsResponseMeanOfTransportItem) ? get_class($getMeanOfTransportsResponseMeanOfTransportItem) : sprintf('%s(%s)', gettype($getMeanOfTransportsResponseMeanOfTransportItem), var_export($getMeanOfTransportsResponseMeanOfTransportItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The MeanOfTransport property can only contain items of type \StructType\MeanOfTransport, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set MeanOfTransport value
     * @throws \InvalidArgumentException
     * @param \StructType\MeanOfTransport[] $meanOfTransport
     * @return \StructType\GetMeanOfTransportsResponse
     */
    public function setMeanOfTransport(array $meanOfTransport = array())
    {
        // validation for constraint: array
        if ('' !== ($meanOfTransportArrayErrorMessage = self::validateMeanOfTransportForArrayConstraintsFromSetMeanOfTransport($meanOfTransport))) {
            throw new \InvalidArgumentException($meanOfTransportArrayErrorMessage, __LINE__);
        }
        $this->MeanOfTransport = $meanOfTransport;
        return $this;
    }
    /**
     * Add item to MeanOfTransport value
     * @throws \InvalidArgumentException
     * @param \StructType\MeanOfTransport $item
     * @return \StructType\GetMeanOfTransportsResponse
     */
    public function addToMeanOfTransport(\StructType\MeanOfTransport $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\MeanOfTransport) {
            throw new \InvalidArgumentException(sprintf('The MeanOfTransport property can only contain items of type \StructType\MeanOfTransport, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->MeanOfTransport[] = $item;
        return $this;
    }
}
