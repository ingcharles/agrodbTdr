<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetStatementsResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetStatementsResponse
 * @subpackage Structs
 */
class GetStatementsResponse extends AbstractStructBase
{
    /**
     * The Statement
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\Statement[]
     */
    public $Statement;
    /**
     * Constructor method for GetStatementsResponse
     * @uses GetStatementsResponse::setStatement()
     * @param \StructType\Statement[] $statement
     */
    public function __construct(array $statement = array())
    {
        $this
            ->setStatement($statement);
    }
    /**
     * Get Statement value
     * @return \StructType\Statement[]|null
     */
    public function getStatement()
    {
        return $this->Statement;
    }
    /**
     * This method is responsible for validating the values passed to the setStatement method
     * This method is willingly generated in order to preserve the one-line inline validation within the setStatement method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateStatementForArrayConstraintsFromSetStatement(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $getStatementsResponseStatementItem) {
            // validation for constraint: itemType
            if (!$getStatementsResponseStatementItem instanceof \StructType\Statement) {
                $invalidValues[] = is_object($getStatementsResponseStatementItem) ? get_class($getStatementsResponseStatementItem) : sprintf('%s(%s)', gettype($getStatementsResponseStatementItem), var_export($getStatementsResponseStatementItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The Statement property can only contain items of type \StructType\Statement, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set Statement value
     * @throws \InvalidArgumentException
     * @param \StructType\Statement[] $statement
     * @return \StructType\GetStatementsResponse
     */
    public function setStatement(array $statement = array())
    {
        // validation for constraint: array
        if ('' !== ($statementArrayErrorMessage = self::validateStatementForArrayConstraintsFromSetStatement($statement))) {
            throw new \InvalidArgumentException($statementArrayErrorMessage, __LINE__);
        }
        $this->Statement = $statement;
        return $this;
    }
    /**
     * Add item to Statement value
     * @throws \InvalidArgumentException
     * @param \StructType\Statement $item
     * @return \StructType\GetStatementsResponse
     */
    public function addToStatement(\StructType\Statement $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\Statement) {
            throw new \InvalidArgumentException(sprintf('The Statement property can only contain items of type \StructType\Statement, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->Statement[] = $item;
        return $this;
    }
}
