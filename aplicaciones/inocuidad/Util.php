<?php
/**
 * Created by PhpStorm.
 * User: ccarrera
 * Date: 2/23/18
 * Time: 4:39 PM
 */

class Util
{
    public function formatDate($dateStr){
        $formated=$dateStr;
        try {
            if ($dateStr != null) {
                if (strlen(trim($dateStr)) == 0) {
                    $formated = null;
                } else {
                    if (preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $dateStr)) {
                        error_log("Fecha esta en formato: " . $dateStr);
                    } else {
                        list($d, $m, $y) = explode('/', $dateStr);
                        $datetime = new DateTime($y . '/' . $m . '/' . $d);
                        $formated = $datetime->format(DateTime::ATOM);
                    }
                }
            } else
                $formated = null;
        }catch (Exception $e){
            error_log("ERROR AL PARSEAR: ".$dateStr);
            error_log($e->getMessage());
        }
        return $formated;
    }

    public function getSQLWhere($objeto){
        $sqlWHERE = "WHERE 1=1 ";
        if($objeto->{'fecha_inicio'}!=null && $objeto->{'fecha_fin'}!=null){
            if($objeto->{'fecha_inicio'}!="" && $objeto->{'fecha_fin'}!=""){
                $sqlWHERE.=" AND REQ.fecha_solicitud between '".$objeto->{'fecha_inicio'}."' and '".$objeto->{'fecha_fin'}."'";
            }
        }
        if($objeto->{'ic_tipo_requerimiento_id'}!=null && $objeto->{'ic_tipo_requerimiento_id'}>0){
            $sqlWHERE.=" AND REQ.ic_tipo_requerimiento_id=".$objeto->{'ic_tipo_requerimiento_id'};
        }
        if($objeto->{'provincia_id'}!=null && $objeto->{'provincia_id'}>0){
            $sqlWHERE.=" AND REQ.provincia_id=".$objeto->{'provincia_id'};
        }
        if($objeto->{'programa_id'}!=null && $objeto->{'programa_id'}>0){
            $sqlWHERE.=" AND REQ.programa_id=".$objeto->{'programa_id'};
        }
        if($objeto->{'inspector_id'}!=null && $objeto->{'inspector_id'}!=''){
            $sqlWHERE.=" AND REQ.inspector_id='".$objeto->{'inspector_id'}."'";
        }

        return $sqlWHERE;
    }

    function file_upload_max_size() {
        static $max_size = -1;

        if ($max_size < 0) {
            // Iniciamos con post_max_size.
            $post_max_size = $this->parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // Si upload_max_size es menor. excepto si upload_max_size = zero, sin limite.
            $upload_max = $this->parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }

    function parse_size($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }
}