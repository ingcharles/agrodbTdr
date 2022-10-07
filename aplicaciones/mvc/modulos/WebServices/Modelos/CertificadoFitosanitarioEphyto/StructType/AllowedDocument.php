<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for allowedDocument StructType
 * @subpackage Structs
 */
class AllowedDocument extends BaseEntity
{
    /**
     * The active
     * Meta information extracted from the WSDL
     * - use: required
     * @var bool
     */
    public $active;
    /**
     * The certificateType
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var \StructType\CertificateType
     */
    public $certificateType;
    /**
     * The certificateStatus
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var \StructType\CertificateStatus
     */
    public $certificateStatus;
    /**
     * Constructor method for allowedDocument
     * @uses AllowedDocument::setActive()
     * @uses AllowedDocument::setCertificateType()
     * @uses AllowedDocument::setCertificateStatus()
     * @param bool $active
     * @param \StructType\CertificateType $certificateType
     * @param \StructType\CertificateStatus $certificateStatus
     */
    public function __construct($active = null, \StructType\CertificateType $certificateType = null, \StructType\CertificateStatus $certificateStatus = null)
    {
        $this
            ->setActive($active)
            ->setCertificateType($certificateType)
            ->setCertificateStatus($certificateStatus);
    }
    /**
     * Get active value
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }
    /**
     * Set active value
     * @param bool $active
     * @return \StructType\AllowedDocument
     */
    public function setActive($active = null)
    {
        // validation for constraint: boolean
        if (!is_null($active) && !is_bool($active)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a bool, %s given', var_export($active, true), gettype($active)), __LINE__);
        }
        $this->active = $active;
        return $this;
    }
    /**
     * Get certificateType value
     * @return \StructType\CertificateType|null
     */
    public function getCertificateType()
    {
        return $this->certificateType;
    }
    /**
     * Set certificateType value
     * @param \StructType\CertificateType $certificateType
     * @return \StructType\AllowedDocument
     */
    public function setCertificateType(\StructType\CertificateType $certificateType = null)
    {
        $this->certificateType = $certificateType;
        return $this;
    }
    /**
     * Get certificateStatus value
     * @return \StructType\CertificateStatus|null
     */
    public function getCertificateStatus()
    {
        return $this->certificateStatus;
    }
    /**
     * Set certificateStatus value
     * @param \StructType\CertificateStatus $certificateStatus
     * @return \StructType\AllowedDocument
     */
    public function setCertificateStatus(\StructType\CertificateStatus $certificateStatus = null)
    {
        $this->certificateStatus = $certificateStatus;
        return $this;
    }
}
