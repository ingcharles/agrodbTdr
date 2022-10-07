<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ValidatePhytoXMLResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:ValidatePhytoXMLResponse
 * @subpackage Structs
 */
class ValidatePhytoXMLResponse extends AbstractStructBase
{
    /**
     * The ValidationResult
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\ValidationResult[]
     */
    public $ValidationResult;
    /**
     * Constructor method for ValidatePhytoXMLResponse
     * @uses ValidatePhytoXMLResponse::setValidationResult()
     * @param \StructType\ValidationResult[] $validationResult
     */
    public function __construct(array $validationResult = array())
    {
        $this
            ->setValidationResult($validationResult);
    }
    /**
     * Get ValidationResult value
     * @return \StructType\ValidationResult[]|null
     */
    public function getValidationResult()
    {
        return $this->ValidationResult;
    }
    /**
     * This method is responsible for validating the values passed to the setValidationResult method
     * This method is willingly generated in order to preserve the one-line inline validation within the setValidationResult method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateValidationResultForArrayConstraintsFromSetValidationResult(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $validatePhytoXMLResponseValidationResultItem) {
            // validation for constraint: itemType
            if (!$validatePhytoXMLResponseValidationResultItem instanceof \StructType\ValidationResult) {
                $invalidValues[] = is_object($validatePhytoXMLResponseValidationResultItem) ? get_class($validatePhytoXMLResponseValidationResultItem) : sprintf('%s(%s)', gettype($validatePhytoXMLResponseValidationResultItem), var_export($validatePhytoXMLResponseValidationResultItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The ValidationResult property can only contain items of type \StructType\ValidationResult, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set ValidationResult value
     * @throws \InvalidArgumentException
     * @param \StructType\ValidationResult[] $validationResult
     * @return \StructType\ValidatePhytoXMLResponse
     */
    public function setValidationResult(array $validationResult = array())
    {
        // validation for constraint: array
        if ('' !== ($validationResultArrayErrorMessage = self::validateValidationResultForArrayConstraintsFromSetValidationResult($validationResult))) {
            throw new \InvalidArgumentException($validationResultArrayErrorMessage, __LINE__);
        }
        $this->ValidationResult = $validationResult;
        return $this;
    }
    /**
     * Add item to ValidationResult value
     * @throws \InvalidArgumentException
     * @param \StructType\ValidationResult $item
     * @return \StructType\ValidatePhytoXMLResponse
     */
    public function addToValidationResult(\StructType\ValidationResult $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\ValidationResult) {
            throw new \InvalidArgumentException(sprintf('The ValidationResult property can only contain items of type \StructType\ValidationResult, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->ValidationResult[] = $item;
        return $this;
    }
}
