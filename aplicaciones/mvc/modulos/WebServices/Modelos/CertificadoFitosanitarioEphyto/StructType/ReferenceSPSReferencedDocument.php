<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for referenceSPSReferencedDocument StructType
 * @subpackage Structs
 */
class ReferenceSPSReferencedDocument extends AbstractStructBase
{
    /**
     * The IssueDateTime
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:IssueDateTime
     * @var \StructType\IssueDateTime
     */
    public $IssueDateTime;
    /**
     * The TypeCode
     * Meta information extracted from the WSDL
     * - ref: ns3:TypeCode
     * @var int
     */
    public $TypeCode;
    /**
     * The RelationshipTypeCode
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:RelationshipTypeCode
     * @var string
     */
    public $RelationshipTypeCode;
    /**
     * The ID
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:ID
     * @var string
     */
    public $ID;
    /**
     * The AttachmentBinaryObject
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:AttachmentBinaryObject
     * @var \StructType\BinaryObjectType
     */
    public $AttachmentBinaryObject;
    /**
     * The Information
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:Information
     * @var \StructType\TextType
     */
    public $Information;
    /**
     * Constructor method for referenceSPSReferencedDocument
     * @uses ReferenceSPSReferencedDocument::setIssueDateTime()
     * @uses ReferenceSPSReferencedDocument::setTypeCode()
     * @uses ReferenceSPSReferencedDocument::setRelationshipTypeCode()
     * @uses ReferenceSPSReferencedDocument::setID()
     * @uses ReferenceSPSReferencedDocument::setAttachmentBinaryObject()
     * @uses ReferenceSPSReferencedDocument::setInformation()
     * @param \StructType\IssueDateTime $issueDateTime
     * @param int $typeCode
     * @param string $relationshipTypeCode
     * @param string $iD
     * @param \StructType\BinaryObjectType $attachmentBinaryObject
     * @param \StructType\TextType $information
     */
    public function __construct(\StructType\IssueDateTime $issueDateTime = null, $typeCode = null, $relationshipTypeCode = null, $iD = null, \StructType\BinaryObjectType $attachmentBinaryObject = null, \StructType\TextType $information = null)
    {
        $this
            ->setIssueDateTime($issueDateTime)
            ->setTypeCode($typeCode)
            ->setRelationshipTypeCode($relationshipTypeCode)
            ->setID($iD)
            ->setAttachmentBinaryObject($attachmentBinaryObject)
            ->setInformation($information);
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
     * @return \StructType\ReferenceSPSReferencedDocument
     */
    public function setIssueDateTime(\StructType\IssueDateTime $issueDateTime = null)
    {
        $this->IssueDateTime = $issueDateTime;
        return $this;
    }
    /**
     * Get TypeCode value
     * @return int|null
     */
    public function getTypeCode()
    {
        return $this->TypeCode;
    }
    /**
     * Set TypeCode value
     * @param int $typeCode
     * @return \StructType\ReferenceSPSReferencedDocument
     */
    public function setTypeCode($typeCode = null)
    {
        // validation for constraint: int
        if (!is_null($typeCode) && !(is_int($typeCode) || ctype_digit($typeCode))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($typeCode, true), gettype($typeCode)), __LINE__);
        }
        $this->TypeCode = $typeCode;
        return $this;
    }
    /**
     * Get RelationshipTypeCode value
     * @return string|null
     */
    public function getRelationshipTypeCode()
    {
        return $this->RelationshipTypeCode;
    }
    /**
     * Set RelationshipTypeCode value
     * @param string $relationshipTypeCode
     * @return \StructType\ReferenceSPSReferencedDocument
     */
    public function setRelationshipTypeCode($relationshipTypeCode = null)
    {
        // validation for constraint: string
        if (!is_null($relationshipTypeCode) && !is_string($relationshipTypeCode)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($relationshipTypeCode, true), gettype($relationshipTypeCode)), __LINE__);
        }
        $this->RelationshipTypeCode = $relationshipTypeCode;
        return $this;
    }
    /**
     * Get ID value
     * @return string|null
     */
    public function getID()
    {
        return $this->ID;
    }
    /**
     * Set ID value
     * @param string $iD
     * @return \StructType\ReferenceSPSReferencedDocument
     */
    public function setID($iD = null)
    {
        // validation for constraint: string
        if (!is_null($iD) && !is_string($iD)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($iD, true), gettype($iD)), __LINE__);
        }
        $this->ID = $iD;
        return $this;
    }
    /**
     * Get AttachmentBinaryObject value
     * @return \StructType\BinaryObjectType|null
     */
    public function getAttachmentBinaryObject()
    {
        return $this->AttachmentBinaryObject;
    }
    /**
     * Set AttachmentBinaryObject value
     * @param \StructType\BinaryObjectType $attachmentBinaryObject
     * @return \StructType\ReferenceSPSReferencedDocument
     */
    public function setAttachmentBinaryObject(\StructType\BinaryObjectType $attachmentBinaryObject = null)
    {
        $this->AttachmentBinaryObject = $attachmentBinaryObject;
        return $this;
    }
    /**
     * Get Information value
     * @return \StructType\TextType|null
     */
    public function getInformation()
    {
        return $this->Information;
    }
    /**
     * Set Information value
     * @param \StructType\TextType $information
     * @return \StructType\ReferenceSPSReferencedDocument
     */
    public function setInformation(\StructType\TextType $information = null)
    {
        $this->Information = $information;
        return $this;
    }
}
