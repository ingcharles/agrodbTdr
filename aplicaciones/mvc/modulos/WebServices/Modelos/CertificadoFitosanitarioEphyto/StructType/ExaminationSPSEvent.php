<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for examinationSPSEvent StructType
 * @subpackage Structs
 */
class ExaminationSPSEvent extends AbstractStructBase
{
    /**
     * The OccurrenceSPSLocation
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \StructType\OccurrenceSPSLocation
     */
    public $OccurrenceSPSLocation;
    /**
     * Constructor method for examinationSPSEvent
     * @uses ExaminationSPSEvent::setOccurrenceSPSLocation()
     * @param \StructType\OccurrenceSPSLocation $occurrenceSPSLocation
     */
    public function __construct(\StructType\OccurrenceSPSLocation $occurrenceSPSLocation = null)
    {
        $this
            ->setOccurrenceSPSLocation($occurrenceSPSLocation);
    }
    /**
     * Get OccurrenceSPSLocation value
     * @return \StructType\OccurrenceSPSLocation|null
     */
    public function getOccurrenceSPSLocation()
    {
        return $this->OccurrenceSPSLocation;
    }
    /**
     * Set OccurrenceSPSLocation value
     * @param \StructType\OccurrenceSPSLocation $occurrenceSPSLocation
     * @return \StructType\ExaminationSPSEvent
     */
    public function setOccurrenceSPSLocation(\StructType\OccurrenceSPSLocation $occurrenceSPSLocation = null)
    {
        $this->OccurrenceSPSLocation = $occurrenceSPSLocation;
        return $this;
    }
}
