<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetPackageTypesResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetPackageTypesResponse
 * @subpackage Structs
 */
class GetPackageTypesResponse extends AbstractStructBase
{
    /**
     * The PackageType
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\PackageType[]
     */
    public $PackageType;
    /**
     * Constructor method for GetPackageTypesResponse
     * @uses GetPackageTypesResponse::setPackageType()
     * @param \StructType\PackageType[] $packageType
     */
    public function __construct(array $packageType = array())
    {
        $this
            ->setPackageType($packageType);
    }
    /**
     * Get PackageType value
     * @return \StructType\PackageType[]|null
     */
    public function getPackageType()
    {
        return $this->PackageType;
    }
    /**
     * This method is responsible for validating the values passed to the setPackageType method
     * This method is willingly generated in order to preserve the one-line inline validation within the setPackageType method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validatePackageTypeForArrayConstraintsFromSetPackageType(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $getPackageTypesResponsePackageTypeItem) {
            // validation for constraint: itemType
            if (!$getPackageTypesResponsePackageTypeItem instanceof \StructType\PackageType) {
                $invalidValues[] = is_object($getPackageTypesResponsePackageTypeItem) ? get_class($getPackageTypesResponsePackageTypeItem) : sprintf('%s(%s)', gettype($getPackageTypesResponsePackageTypeItem), var_export($getPackageTypesResponsePackageTypeItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The PackageType property can only contain items of type \StructType\PackageType, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set PackageType value
     * @throws \InvalidArgumentException
     * @param \StructType\PackageType[] $packageType
     * @return \StructType\GetPackageTypesResponse
     */
    public function setPackageType(array $packageType = array())
    {
        // validation for constraint: array
        if ('' !== ($packageTypeArrayErrorMessage = self::validatePackageTypeForArrayConstraintsFromSetPackageType($packageType))) {
            throw new \InvalidArgumentException($packageTypeArrayErrorMessage, __LINE__);
        }
        $this->PackageType = $packageType;
        return $this;
    }
    /**
     * Add item to PackageType value
     * @throws \InvalidArgumentException
     * @param \StructType\PackageType $item
     * @return \StructType\GetPackageTypesResponse
     */
    public function addToPackageType(\StructType\PackageType $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\PackageType) {
            throw new \InvalidArgumentException(sprintf('The PackageType property can only contain items of type \StructType\PackageType, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->PackageType[] = $item;
        return $this;
    }
}
