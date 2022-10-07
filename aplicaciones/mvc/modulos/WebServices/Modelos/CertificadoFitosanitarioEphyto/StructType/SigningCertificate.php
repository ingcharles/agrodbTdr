<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for signingCertificate StructType
 * @subpackage Structs
 */
class SigningCertificate extends BaseEntity
{
    /**
     * The dn
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $dn;
    /**
     * The certificate
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $certificate;
    /**
     * Constructor method for signingCertificate
     * @uses SigningCertificate::setDn()
     * @uses SigningCertificate::setCertificate()
     * @param string $dn
     * @param string $certificate
     */
    public function __construct($dn = null, $certificate = null)
    {
        $this
            ->setDn($dn)
            ->setCertificate($certificate);
    }
    /**
     * Get dn value
     * @return string|null
     */
    public function getDn()
    {
        return $this->dn;
    }
    /**
     * Set dn value
     * @param string $dn
     * @return \StructType\SigningCertificate
     */
    public function setDn($dn = null)
    {
        // validation for constraint: string
        if (!is_null($dn) && !is_string($dn)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($dn, true), gettype($dn)), __LINE__);
        }
        $this->dn = $dn;
        return $this;
    }
    /**
     * Get certificate value
     * @return string|null
     */
    public function getCertificate()
    {
        return $this->certificate;
    }
    /**
     * Set certificate value
     * @param string $certificate
     * @return \StructType\SigningCertificate
     */
    public function setCertificate($certificate = null)
    {
        // validation for constraint: string
        if (!is_null($certificate) && !is_string($certificate)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($certificate, true), gettype($certificate)), __LINE__);
        }
        $this->certificate = $certificate;
        return $this;
    }
}
