<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for spsAcknowledgementDocument StructType
 * @subpackage Structs
 */
class SpsAcknowledgementDocument extends AbstractStructBase
{
    /**
     * The IssueDateTime
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: tns:IssueDateTime
     * @var \StructType\IssueDateTime
     */
    public $IssueDateTime;
    /**
     * The StatusCode
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var int
     */
    public $StatusCode;
    /**
     * The ReasonInformation
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \StructType\TextType
     */
    public $ReasonInformation;
    /**
     * The ReferenceSPSReferencedDocument
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - ref: tns:ReferenceSPSReferencedDocument
     * @var \StructType\ReferenceSPSReferencedDocument[]
     */
    public $ReferenceSPSReferencedDocument;
    /**
     * Constructor method for spsAcknowledgementDocument
     * @uses SpsAcknowledgementDocument::setIssueDateTime()
     * @uses SpsAcknowledgementDocument::setStatusCode()
     * @uses SpsAcknowledgementDocument::setReasonInformation()
     * @uses SpsAcknowledgementDocument::setReferenceSPSReferencedDocument()
     * @param \StructType\IssueDateTime $issueDateTime
     * @param int $statusCode
     * @param \StructType\TextType $reasonInformation
     * @param \StructType\ReferenceSPSReferencedDocument[] $referenceSPSReferencedDocument
     */
    public function __construct(\StructType\IssueDateTime $issueDateTime = null, $statusCode = null, \StructType\TextType $reasonInformation = null, array $referenceSPSReferencedDocument = array())
    {
        $this
            ->setIssueDateTime($issueDateTime)
            ->setStatusCode($statusCode)
            ->setReasonInformation($reasonInformation)
            ->setReferenceSPSReferencedDocument($referenceSPSReferencedDocument);
    }
    /**
     * Get IssueDateTime value
     * @return \StructType\IssueDateTime|null
     */
    public function getIssueDateTime()
    {
        return $this->IssueDateTime;
    }
    /**
     * Set IssueDateTime value
     * @param \StructType\IssueDateTime $issueDateTime
     * @return \StructType\SpsAcknowledgementDocument
     */
    public function setIssueDateTime(\StructType\IssueDateTime $issueDateTime = null)
    {
        $this->IssueDateTime = $issueDateTime;
        return $this;
    }
    /**
     * Get StatusCode value
     * @return int|null
     */
    public function getStatusCode()
    {
        return $this->StatusCode;
    }
    /**
     * Set StatusCode value
     * @param int $statusCode
     * @return \StructType\SpsAcknowledgementDocument
     */
    public function setStatusCode($statusCode = null)
    {
        // validation for constraint: int
        if (!is_null($statusCode) && !(is_int($statusCode) || ctype_digit($statusCode))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($statusCode, true), gettype($statusCode)), __LINE__);
        }
        $this->StatusCode = $statusCode;
        return $this;
    }
    /**
     * Get ReasonInformation value
     * @return \StructType\TextType|null
     */
    public function getReasonInformation()
    {
        return $this->ReasonInformation;
    }
    /**
     * Set ReasonInformation value
     * @param \StructType\TextType $reasonInformation
     * @return \StructType\SpsAcknowledgementDocument
     */
    public function setReasonInformation(\StructType\TextType $reasonInformation = null)
    {
        $this->ReasonInformation = $reasonInformation;
        return $this;
    }
    /**
     * Get ReferenceSPSReferencedDocument value
     * @return \StructType\ReferenceSPSReferencedDocument[]|null
     */
    public function getReferenceSPSReferencedDocument()
    {
        return $this->ReferenceSPSReferencedDocument;
    }
    /**
     * This method is responsible for validating the values passed to the setReferenceSPSReferencedDocument method
     * This method is willingly generated in order to preserve the one-line inline validation within the setReferenceSPSReferencedDocument method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateReferenceSPSReferencedDocumentForArrayConstraintsFromSetReferenceSPSReferencedDocument(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $spsAcknowledgementDocumentReferenceSPSReferencedDocumentItem) {
            // validation for constraint: itemType
            if (!$spsAcknowledgementDocumentReferenceSPSReferencedDocumentItem instanceof \StructType\ReferenceSPSReferencedDocument) {
                $invalidValues[] = is_object($spsAcknowledgementDocumentReferenceSPSReferencedDocumentItem) ? get_class($spsAcknowledgementDocumentReferenceSPSReferencedDocumentItem) : sprintf('%s(%s)', gettype($spsAcknowledgementDocumentReferenceSPSReferencedDocumentItem), var_export($spsAcknowledgementDocumentReferenceSPSReferencedDocumentItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The ReferenceSPSReferencedDocument property can only contain items of type \StructType\ReferenceSPSReferencedDocument, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set ReferenceSPSReferencedDocument value
     * @throws \InvalidArgumentException
     * @param \StructType\ReferenceSPSReferencedDocument[] $referenceSPSReferencedDocument
     * @return \StructType\SpsAcknowledgementDocument
     */
    public function setReferenceSPSReferencedDocument(array $referenceSPSReferencedDocument = array())
    {
        // validation for constraint: array
        if ('' !== ($referenceSPSReferencedDocumentArrayErrorMessage = self::validateReferenceSPSReferencedDocumentForArrayConstraintsFromSetReferenceSPSReferencedDocument($referenceSPSReferencedDocument))) {
            throw new \InvalidArgumentException($referenceSPSReferencedDocumentArrayErrorMessage, __LINE__);
        }
        $this->ReferenceSPSReferencedDocument = $referenceSPSReferencedDocument;
        return $this;
    }
    /**
     * Add item to ReferenceSPSReferencedDocument value
     * @throws \InvalidArgumentException
     * @param \StructType\ReferenceSPSReferencedDocument $item
     * @return \StructType\SpsAcknowledgementDocument
     */
    public function addToReferenceSPSReferencedDocument(\StructType\ReferenceSPSReferencedDocument $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\ReferenceSPSReferencedDocument) {
            throw new \InvalidArgumentException(sprintf('The ReferenceSPSReferencedDocument property can only contain items of type \StructType\ReferenceSPSReferencedDocument, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->ReferenceSPSReferencedDocument[] = $item;
        return $this;
    }
}
