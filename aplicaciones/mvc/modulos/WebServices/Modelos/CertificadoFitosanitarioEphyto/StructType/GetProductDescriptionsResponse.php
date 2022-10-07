<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetProductDescriptionsResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetProductDescriptionsResponse
 * @subpackage Structs
 */
class GetProductDescriptionsResponse extends AbstractStructBase
{
    /**
     * The ProductDescription
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\ProductDescription[]
     */
    public $ProductDescription;
    /**
     * Constructor method for GetProductDescriptionsResponse
     * @uses GetProductDescriptionsResponse::setProductDescription()
     * @param \StructType\ProductDescription[] $productDescription
     */
    public function __construct(array $productDescription = array())
    {
        $this
            ->setProductDescription($productDescription);
    }
    /**
     * Get ProductDescription value
     * @return \StructType\ProductDescription[]|null
     */
    public function getProductDescription()
    {
        return $this->ProductDescription;
    }
    /**
     * This method is responsible for validating the values passed to the setProductDescription method
     * This method is willingly generated in order to preserve the one-line inline validation within the setProductDescription method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateProductDescriptionForArrayConstraintsFromSetProductDescription(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $getProductDescriptionsResponseProductDescriptionItem) {
            // validation for constraint: itemType
            if (!$getProductDescriptionsResponseProductDescriptionItem instanceof \StructType\ProductDescription) {
                $invalidValues[] = is_object($getProductDescriptionsResponseProductDescriptionItem) ? get_class($getProductDescriptionsResponseProductDescriptionItem) : sprintf('%s(%s)', gettype($getProductDescriptionsResponseProductDescriptionItem), var_export($getProductDescriptionsResponseProductDescriptionItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The ProductDescription property can only contain items of type \StructType\ProductDescription, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set ProductDescription value
     * @throws \InvalidArgumentException
     * @param \StructType\ProductDescription[] $productDescription
     * @return \StructType\GetProductDescriptionsResponse
     */
    public function setProductDescription(array $productDescription = array())
    {
        // validation for constraint: array
        if ('' !== ($productDescriptionArrayErrorMessage = self::validateProductDescriptionForArrayConstraintsFromSetProductDescription($productDescription))) {
            throw new \InvalidArgumentException($productDescriptionArrayErrorMessage, __LINE__);
        }
        $this->ProductDescription = $productDescription;
        return $this;
    }
    /**
     * Add item to ProductDescription value
     * @throws \InvalidArgumentException
     * @param \StructType\ProductDescription $item
     * @return \StructType\GetProductDescriptionsResponse
     */
    public function addToProductDescription(\StructType\ProductDescription $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\ProductDescription) {
            throw new \InvalidArgumentException(sprintf('The ProductDescription property can only contain items of type \StructType\ProductDescription, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->ProductDescription[] = $item;
        return $this;
    }
}
