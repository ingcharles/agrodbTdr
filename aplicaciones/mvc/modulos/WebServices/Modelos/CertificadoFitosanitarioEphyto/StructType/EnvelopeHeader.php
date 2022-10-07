<?php

namespace Agrodb\WebServices\Modelos\CertificadoFitosanitarioEphyto\StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for EnvelopeHeader StructType
 * Meta information extracted from the WSDL
 * - type: tns:EnvelopeHeader
 * @subpackage Structs
 */
class EnvelopeHeader extends AbstractStructBase
{
    /**
     * The From
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var string
     */
    public $From;
    /**
     * The To
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var string
     */
    public $To;
    /**
     * The CertificateType
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var int
     */
    public $CertificateType;
    /**
     * The CertificateStatus
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var int
     */
    public $CertificateStatus;
    /**
     * The NPPOCertificateNumber
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var string
     */
    public $NPPOCertificateNumber;
    /**
     * The hubDeliveryNumber
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $hubDeliveryNumber;
    /**
     * The HUBTrackingInfo
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $HUBTrackingInfo;
    /**
     * The hubDeliveryErrorMessage
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $hubDeliveryErrorMessage;
    /**
     * The Forwardings
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \ArrayType\ArrayOfEnvelopeForwarding
     */
    public $Forwardings;
    /**
     * Constructor method for EnvelopeHeader
     * @uses EnvelopeHeader::setFrom()
     * @uses EnvelopeHeader::setTo()
     * @uses EnvelopeHeader::setCertificateType()
     * @uses EnvelopeHeader::setCertificateStatus()
     * @uses EnvelopeHeader::setNPPOCertificateNumber()
     * @uses EnvelopeHeader::setHubDeliveryNumber()
     * @uses EnvelopeHeader::setHUBTrackingInfo()
     * @uses EnvelopeHeader::setHubDeliveryErrorMessage()
     * @uses EnvelopeHeader::setForwardings()
     * @param string $from
     * @param string $to
     * @param int $certificateType
     * @param int $certificateStatus
     * @param string $nPPOCertificateNumber
     * @param string $hubDeliveryNumber
     * @param string $hUBTrackingInfo
     * @param string $hubDeliveryErrorMessage
     * @param \ArrayType\ArrayOfEnvelopeForwarding $forwardings
     */
    public function __construct($from = null, $to = null, $certificateType = null, $certificateStatus = null, $nPPOCertificateNumber = null, $hubDeliveryNumber = null, $hUBTrackingInfo = null, $hubDeliveryErrorMessage = null, \ArrayType\ArrayOfEnvelopeForwarding $forwardings = null)
    {
        $this
            ->setFrom($from)
            ->setTo($to)
            ->setCertificateType($certificateType)
            ->setCertificateStatus($certificateStatus)
            ->setNPPOCertificateNumber($nPPOCertificateNumber)
            ->setHubDeliveryNumber($hubDeliveryNumber)
            ->setHUBTrackingInfo($hUBTrackingInfo)
            ->setHubDeliveryErrorMessage($hubDeliveryErrorMessage)
            ->setForwardings($forwardings);
    }
    /**
     * Get From value
     * @return string|null
     */
    public function getFrom()
    {
        return $this->From;
    }
    /**
     * Set From value
     * @param string $from
     * @return \StructType\EnvelopeHeader
     */
    public function setFrom($from = null)
    {
        // validation for constraint: string
        if (!is_null($from) && !is_string($from)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($from, true), gettype($from)), __LINE__);
        }
        $this->From = $from;
        return $this;
    }
    /**
     * Get To value
     * @return string|null
     */
    public function getTo()
    {
        return $this->To;
    }
    /**
     * Set To value
     * @param string $to
     * @return \StructType\EnvelopeHeader
     */
    public function setTo($to = null)
    {
        // validation for constraint: string
        if (!is_null($to) && !is_string($to)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($to, true), gettype($to)), __LINE__);
        }
        $this->To = $to;
        return $this;
    }
    /**
     * Get CertificateType value
     * @return int|null
     */
    public function getCertificateType()
    {
        return $this->CertificateType;
    }
    /**
     * Set CertificateType value
     * @param int $certificateType
     * @return \StructType\EnvelopeHeader
     */
    public function setCertificateType($certificateType = null)
    {
        // validation for constraint: int
        if (!is_null($certificateType) && !(is_int($certificateType) || ctype_digit($certificateType))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($certificateType, true), gettype($certificateType)), __LINE__);
        }
        $this->CertificateType = $certificateType;
        return $this;
    }
    /**
     * Get CertificateStatus value
     * @return int|null
     */
    public function getCertificateStatus()
    {
        return $this->CertificateStatus;
    }
    /**
     * Set CertificateStatus value
     * @param int $certificateStatus
     * @return \StructType\EnvelopeHeader
     */
    public function setCertificateStatus($certificateStatus = null)
    {
        // validation for constraint: int
        if (!is_null($certificateStatus) && !(is_int($certificateStatus) || ctype_digit($certificateStatus))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($certificateStatus, true), gettype($certificateStatus)), __LINE__);
        }
        $this->CertificateStatus = $certificateStatus;
        return $this;
    }
    /**
     * Get NPPOCertificateNumber value
     * @return string|null
     */
    public function getNPPOCertificateNumber()
    {
        return $this->NPPOCertificateNumber;
    }
    /**
     * Set NPPOCertificateNumber value
     * @param string $nPPOCertificateNumber
     * @return \StructType\EnvelopeHeader
     */
    public function setNPPOCertificateNumber($nPPOCertificateNumber = null)
    {
        // validation for constraint: string
        if (!is_null($nPPOCertificateNumber) && !is_string($nPPOCertificateNumber)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($nPPOCertificateNumber, true), gettype($nPPOCertificateNumber)), __LINE__);
        }
        $this->NPPOCertificateNumber = $nPPOCertificateNumber;
        return $this;
    }
    /**
     * Get hubDeliveryNumber value
     * @return string|null
     */
    public function getHubDeliveryNumber()
    {
        return $this->hubDeliveryNumber;
    }
    /**
     * Set hubDeliveryNumber value
     * @param string $hubDeliveryNumber
     * @return \StructType\EnvelopeHeader
     */
    public function setHubDeliveryNumber($hubDeliveryNumber = null)
    {
        // validation for constraint: string
        if (!is_null($hubDeliveryNumber) && !is_string($hubDeliveryNumber)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($hubDeliveryNumber, true), gettype($hubDeliveryNumber)), __LINE__);
        }
        $this->hubDeliveryNumber = $hubDeliveryNumber;
        return $this;
    }
    /**
     * Get HUBTrackingInfo value
     * @return string|null
     */
    public function getHUBTrackingInfo()
    {
        return $this->HUBTrackingInfo;
    }
    /**
     * Set HUBTrackingInfo value
     * @param string $hUBTrackingInfo
     * @return \StructType\EnvelopeHeader
     */
    public function setHUBTrackingInfo($hUBTrackingInfo = null)
    {
        // validation for constraint: string
        if (!is_null($hUBTrackingInfo) && !is_string($hUBTrackingInfo)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($hUBTrackingInfo, true), gettype($hUBTrackingInfo)), __LINE__);
        }
        $this->HUBTrackingInfo = $hUBTrackingInfo;
        return $this;
    }
    /**
     * Get hubDeliveryErrorMessage value
     * @return string|null
     */
    public function getHubDeliveryErrorMessage()
    {
        return $this->hubDeliveryErrorMessage;
    }
    /**
     * Set hubDeliveryErrorMessage value
     * @param string $hubDeliveryErrorMessage
     * @return \StructType\EnvelopeHeader
     */
    public function setHubDeliveryErrorMessage($hubDeliveryErrorMessage = null)
    {
        // validation for constraint: string
        if (!is_null($hubDeliveryErrorMessage) && !is_string($hubDeliveryErrorMessage)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($hubDeliveryErrorMessage, true), gettype($hubDeliveryErrorMessage)), __LINE__);
        }
        $this->hubDeliveryErrorMessage = $hubDeliveryErrorMessage;
        return $this;
    }
    /**
     * Get Forwardings value
     * @return \ArrayType\ArrayOfEnvelopeForwarding|null
     */
    public function getForwardings()
    {
        return $this->Forwardings;
    }
    /**
     * Set Forwardings value
     * @param \ArrayType\ArrayOfEnvelopeForwarding $forwardings
     * @return \StructType\EnvelopeHeader
     */
    public function setForwardings(\ArrayType\ArrayOfEnvelopeForwarding $forwardings = null)
    {
        $this->Forwardings = $forwardings;
        return $this;
    }
}
