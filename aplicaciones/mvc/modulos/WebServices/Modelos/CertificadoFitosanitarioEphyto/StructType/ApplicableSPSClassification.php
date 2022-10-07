<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for applicableSPSClassification StructType
 * @subpackage Structs
 */
class ApplicableSPSClassification extends AbstractStructBase
{
    /**
     * The SystemName
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:SystemName
     * @var string
     */
    public $SystemName;
    /**
     * The ClassCode
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:ClassCode
     * @var string
     */
    public $ClassCode;
    /**
     * The ClassName
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - ref: ns3:ClassName
     * @var \StructType\TextType[]
     */
    public $ClassName;
    /**
     * Constructor method for applicableSPSClassification
     * @uses ApplicableSPSClassification::setSystemName()
     * @uses ApplicableSPSClassification::setClassCode()
     * @uses ApplicableSPSClassification::setClassName()
     * @param string $systemName
     * @param string $classCode
     * @param \StructType\TextType[] $className
     */
    public function __construct($systemName = null, $classCode = null, array $className = array())
    {
        $this
            ->setSystemName($systemName)
            ->setClassCode($classCode)
            ->setClassName($className);
    }
    /**
     * Get SystemName value
     * @return string|null
     */
    public function getSystemName()
    {
        return $this->SystemName;
    }
    /**
     * Set SystemName value
     * @param string $systemName
     * @return \StructType\ApplicableSPSClassification
     */
    public function setSystemName($systemName = null)
    {
        // validation for constraint: string
        if (!is_null($systemName) && !is_string($systemName)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($systemName, true), gettype($systemName)), __LINE__);
        }
        $this->SystemName = $systemName;
        return $this;
    }
    /**
     * Get ClassCode value
     * @return string|null
     */
    public function getClassCode()
    {
        return $this->ClassCode;
    }
    /**
     * Set ClassCode value
     * @param string $classCode
     * @return \StructType\ApplicableSPSClassification
     */
    public function setClassCode($classCode = null)
    {
        // validation for constraint: string
        if (!is_null($classCode) && !is_string($classCode)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($classCode, true), gettype($classCode)), __LINE__);
        }
        $this->ClassCode = $classCode;
        return $this;
    }
    /**
     * Get ClassName value
     * @return \StructType\TextType[]|null
     */
    public function getClassName()
    {
        return $this->ClassName;
    }
    /**
     * This method is responsible for validating the values passed to the setClassName method
     * This method is willingly generated in order to preserve the one-line inline validation within the setClassName method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateClassNameForArrayConstraintsFromSetClassName(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $applicableSPSClassificationClassNameItem) {
            // validation for constraint: itemType
            if (!$applicableSPSClassificationClassNameItem instanceof \StructType\TextType) {
                $invalidValues[] = is_object($applicableSPSClassificationClassNameItem) ? get_class($applicableSPSClassificationClassNameItem) : sprintf('%s(%s)', gettype($applicableSPSClassificationClassNameItem), var_export($applicableSPSClassificationClassNameItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The ClassName property can only contain items of type \StructType\TextType, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set ClassName value
     * @throws \InvalidArgumentException
     * @param \StructType\TextType[] $className
     * @return \StructType\ApplicableSPSClassification
     */
    public function setClassName(array $className = array())
    {
        // validation for constraint: array
        if ('' !== ($classNameArrayErrorMessage = self::validateClassNameForArrayConstraintsFromSetClassName($className))) {
            throw new \InvalidArgumentException($classNameArrayErrorMessage, __LINE__);
        }
        $this->ClassName = $className;
        return $this;
    }
    /**
     * Add item to ClassName value
     * @throws \InvalidArgumentException
     * @param \StructType\TextType $item
     * @return \StructType\ApplicableSPSClassification
     */
    public function addToClassName(\StructType\TextType $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\TextType) {
            throw new \InvalidArgumentException(sprintf('The ClassName property can only contain items of type \StructType\TextType, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->ClassName[] = $item;
        return $this;
    }
}
