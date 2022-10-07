<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetAdditionalDeclarationsResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetAdditionalDeclarationsResponse
 * @subpackage Structs
 */
class GetAdditionalDeclarationsResponse extends AbstractStructBase
{
    /**
     * The AdditionalDeclaration
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\AdditionalDeclaration[]
     */
    public $AdditionalDeclaration;
    /**
     * Constructor method for GetAdditionalDeclarationsResponse
     * @uses GetAdditionalDeclarationsResponse::setAdditionalDeclaration()
     * @param \StructType\AdditionalDeclaration[] $additionalDeclaration
     */
    public function __construct(array $additionalDeclaration = array())
    {
        $this
            ->setAdditionalDeclaration($additionalDeclaration);
    }
    /**
     * Get AdditionalDeclaration value
     * @return \StructType\AdditionalDeclaration[]|null
     */
    public function getAdditionalDeclaration()
    {
        return $this->AdditionalDeclaration;
    }
    /**
     * This method is responsible for validating the values passed to the setAdditionalDeclaration method
     * This method is willingly generated in order to preserve the one-line inline validation within the setAdditionalDeclaration method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateAdditionalDeclarationForArrayConstraintsFromSetAdditionalDeclaration(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $getAdditionalDeclarationsResponseAdditionalDeclarationItem) {
            // validation for constraint: itemType
            if (!$getAdditionalDeclarationsResponseAdditionalDeclarationItem instanceof \StructType\AdditionalDeclaration) {
                $invalidValues[] = is_object($getAdditionalDeclarationsResponseAdditionalDeclarationItem) ? get_class($getAdditionalDeclarationsResponseAdditionalDeclarationItem) : sprintf('%s(%s)', gettype($getAdditionalDeclarationsResponseAdditionalDeclarationItem), var_export($getAdditionalDeclarationsResponseAdditionalDeclarationItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The AdditionalDeclaration property can only contain items of type \StructType\AdditionalDeclaration, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set AdditionalDeclaration value
     * @throws \InvalidArgumentException
     * @param \StructType\AdditionalDeclaration[] $additionalDeclaration
     * @return \StructType\GetAdditionalDeclarationsResponse
     */
    public function setAdditionalDeclaration(array $additionalDeclaration = array())
    {
        // validation for constraint: array
        if ('' !== ($additionalDeclarationArrayErrorMessage = self::validateAdditionalDeclarationForArrayConstraintsFromSetAdditionalDeclaration($additionalDeclaration))) {
            throw new \InvalidArgumentException($additionalDeclarationArrayErrorMessage, __LINE__);
        }
        $this->AdditionalDeclaration = $additionalDeclaration;
        return $this;
    }
    /**
     * Add item to AdditionalDeclaration value
     * @throws \InvalidArgumentException
     * @param \StructType\AdditionalDeclaration $item
     * @return \StructType\GetAdditionalDeclarationsResponse
     */
    public function addToAdditionalDeclaration(\StructType\AdditionalDeclaration $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\AdditionalDeclaration) {
            throw new \InvalidArgumentException(sprintf('The AdditionalDeclaration property can only contain items of type \StructType\AdditionalDeclaration, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->AdditionalDeclaration[] = $item;
        return $this;
    }
}
