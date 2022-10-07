<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for spsExchangedDocument StructType
 * @subpackage Structs
 */
class SpsExchangedDocument extends AbstractStructBase
{
    /**
     * The Name
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $Name;
    /**
     * The ID
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $ID;
    /**
     * The TypeCode
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var int
     */
    public $TypeCode;
    /**
     * The StatusCode
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var int
     */
    public $StatusCode;
    /**
     * The IssueDateTime
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: tns:IssueDateTime
     * @var \StructType\IssueDateTime
     */
    public $IssueDateTime;
    /**
     * The IssuerSPSParty
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \StructType\SpsIssuerSPSParty
     */
    public $IssuerSPSParty;
    /**
     * The IncludedSPSNote
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - ref: tns:IncludedSPSNote
     * @var \StructType\IncludedSPSNote[]
     */
    public $IncludedSPSNote;
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
     * The SignatorySPSAuthentication
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: tns:SignatorySPSAuthentication
     * @var \StructType\SignatorySPSAuthentication
     */
    public $SignatorySPSAuthentication;
    /**
     * Constructor method for spsExchangedDocument
     * @uses SpsExchangedDocument::setName()
     * @uses SpsExchangedDocument::setID()
     * @uses SpsExchangedDocument::setTypeCode()
     * @uses SpsExchangedDocument::setStatusCode()
     * @uses SpsExchangedDocument::setIssueDateTime()
     * @uses SpsExchangedDocument::setIssuerSPSParty()
     * @uses SpsExchangedDocument::setIncludedSPSNote()
     * @uses SpsExchangedDocument::setReferenceSPSReferencedDocument()
     * @uses SpsExchangedDocument::setSignatorySPSAuthentication()
     * @param string $name
     * @param string $iD
     * @param int $typeCode
     * @param int $statusCode
     * @param \StructType\IssueDateTime $issueDateTime
     * @param \StructType\SpsIssuerSPSParty $issuerSPSParty
     * @param \StructType\IncludedSPSNote[] $includedSPSNote
     * @param \StructType\ReferenceSPSReferencedDocument[] $referenceSPSReferencedDocument
     * @param \StructType\SignatorySPSAuthentication $signatorySPSAuthentication
     */
    public function __construct($name = null, $iD = null, $typeCode = null, $statusCode = null, \StructType\IssueDateTime $issueDateTime = null, \StructType\SpsIssuerSPSParty $issuerSPSParty = null, array $includedSPSNote = array(), array $referenceSPSReferencedDocument = array(), \StructType\SignatorySPSAuthentication $signatorySPSAuthentication = null)
    {
        $this
            ->setName($name)
            ->setID($iD)
            ->setTypeCode($typeCode)
            ->setStatusCode($statusCode)
            ->setIssueDateTime($issueDateTime)
            ->setIssuerSPSParty($issuerSPSParty)
            ->setIncludedSPSNote($includedSPSNote)
            ->setReferenceSPSReferencedDocument($referenceSPSReferencedDocument)
            ->setSignatorySPSAuthentication($signatorySPSAuthentication);
    }
    /**
     * Get Name value
     * @return string|null
     */
    public function getName()
    {
        return $this->Name;
    }
    /**
     * Set Name value
     * @param string $name
     * @return \StructType\SpsExchangedDocument
     */
    public function setName($name = null)
    {
        // validation for constraint: string
        if (!is_null($name) && !is_string($name)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($name, true), gettype($name)), __LINE__);
        }
        $this->Name = $name;
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
     * @return \StructType\SpsExchangedDocument
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
     * @return \StructType\SpsExchangedDocument
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
     * @return \StructType\SpsExchangedDocument
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
     * @return \StructType\SpsExchangedDocument
     */
    public function setIssueDateTime(\StructType\IssueDateTime $issueDateTime = null)
    {
        $this->IssueDateTime = $issueDateTime;
        return $this;
    }
    /**
     * Get IssuerSPSParty value
     * @return \StructType\SpsIssuerSPSParty|null
     */
    public function getIssuerSPSParty()
    {
        return $this->IssuerSPSParty;
    }
    /**
     * Set IssuerSPSParty value
     * @param \StructType\SpsIssuerSPSParty $issuerSPSParty
     * @return \StructType\SpsExchangedDocument
     */
    public function setIssuerSPSParty(\StructType\SpsIssuerSPSParty $issuerSPSParty = null)
    {
        $this->IssuerSPSParty = $issuerSPSParty;
        return $this;
    }
    /**
     * Get IncludedSPSNote value
     * @return \StructType\IncludedSPSNote[]|null
     */
    public function getIncludedSPSNote()
    {
        return $this->IncludedSPSNote;
    }
    /**
     * This method is responsible for validating the values passed to the setIncludedSPSNote method
     * This method is willingly generated in order to preserve the one-line inline validation within the setIncludedSPSNote method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateIncludedSPSNoteForArrayConstraintsFromSetIncludedSPSNote(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $spsExchangedDocumentIncludedSPSNoteItem) {
            // validation for constraint: itemType
            if (!$spsExchangedDocumentIncludedSPSNoteItem instanceof \StructType\IncludedSPSNote) {
                $invalidValues[] = is_object($spsExchangedDocumentIncludedSPSNoteItem) ? get_class($spsExchangedDocumentIncludedSPSNoteItem) : sprintf('%s(%s)', gettype($spsExchangedDocumentIncludedSPSNoteItem), var_export($spsExchangedDocumentIncludedSPSNoteItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The IncludedSPSNote property can only contain items of type \StructType\IncludedSPSNote, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set IncludedSPSNote value
     * @throws \InvalidArgumentException
     * @param \StructType\IncludedSPSNote[] $includedSPSNote
     * @return \StructType\SpsExchangedDocument
     */
    public function setIncludedSPSNote(array $includedSPSNote = array())
    {
        // validation for constraint: array
        if ('' !== ($includedSPSNoteArrayErrorMessage = self::validateIncludedSPSNoteForArrayConstraintsFromSetIncludedSPSNote($includedSPSNote))) {
            throw new \InvalidArgumentException($includedSPSNoteArrayErrorMessage, __LINE__);
        }
        $this->IncludedSPSNote = $includedSPSNote;
        return $this;
    }
    /**
     * Add item to IncludedSPSNote value
     * @throws \InvalidArgumentException
     * @param \StructType\IncludedSPSNote $item
     * @return \StructType\SpsExchangedDocument
     */
    public function addToIncludedSPSNote(\StructType\IncludedSPSNote $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\IncludedSPSNote) {
            throw new \InvalidArgumentException(sprintf('The IncludedSPSNote property can only contain items of type \StructType\IncludedSPSNote, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->IncludedSPSNote[] = $item;
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
        foreach ($values as $spsExchangedDocumentReferenceSPSReferencedDocumentItem) {
            // validation for constraint: itemType
            if (!$spsExchangedDocumentReferenceSPSReferencedDocumentItem instanceof \StructType\ReferenceSPSReferencedDocument) {
                $invalidValues[] = is_object($spsExchangedDocumentReferenceSPSReferencedDocumentItem) ? get_class($spsExchangedDocumentReferenceSPSReferencedDocumentItem) : sprintf('%s(%s)', gettype($spsExchangedDocumentReferenceSPSReferencedDocumentItem), var_export($spsExchangedDocumentReferenceSPSReferencedDocumentItem, true));
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
     * @return \StructType\SpsExchangedDocument
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
     * @return \StructType\SpsExchangedDocument
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
    /**
     * Get SignatorySPSAuthentication value
     * @return \StructType\SignatorySPSAuthentication|null
     */
    public function getSignatorySPSAuthentication()
    {
        return $this->SignatorySPSAuthentication;
    }
    /**
     * Set SignatorySPSAuthentication value
     * @param \StructType\SignatorySPSAuthentication $signatorySPSAuthentication
     * @return \StructType\SpsExchangedDocument
     */
    public function setSignatorySPSAuthentication(\StructType\SignatorySPSAuthentication $signatorySPSAuthentication = null)
    {
        $this->SignatorySPSAuthentication = $signatorySPSAuthentication;
        return $this;
    }
}
