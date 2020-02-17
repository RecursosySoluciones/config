<?php

require 'connect.php';

// definimos conexion a la base de datos
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_BASE','test');

// Importamos el json con todos los planes actuales en la app para generar funcion y consultar en que tabla se encuentra
$json           = json_decode(file_get_contents('planes_all.json'));
$json_new       = json_decode(file_get_contents('planes_new.json'));

$ini_ph = 3964;
$ini_p = 3459;

foreach($json as $value){
    // Datos para validar db
    $id                         = $value->id;
    $plan                       = $value->Plan;
    $fecha                      = $value->Fecha;
    $abono                      = "";
    $precio                     = "";
    $preciofinal                = "";
    $CreditoDisponibleFinal     = "";
    $OFF30HP                    = "";
    $OFF30HPFinal               = "";
    $MBGBincl                   = "";
    $SegON                      = "";
    $SegOFF                     = "";
    $SMS                        = "";
    $DatosexcFinal              = "";
    $existe                     = false;


    $example = @consultar_planes($id,$plan,$fecha,$Vigencia);

    foreach($json_new as $value2){
        if($value2->PLAN == $plan){
            $abono                      = $value2->NOMBRE;
            $precio                     = $value2->PRECIO_S_IMP;
            $preciofinal                = $value2->PRECIO_C_IMP;
            $CreditoDisponibleFinal     = $value2->C_DISPONIBLE;
            $OFF30HP                    = $value2->B30_VOF_SIMP;
            $OFF30HPFinal               = $value2->B30_VOF_CIMP;
            $MBGBincl                   = $value2->CUO_INT;
            $SegON                      = $value2->ONNET;
            $SegOFF                     = $value2->OFFNET;
            $SMS                        = $value2->SMS;
            $DatosexcFinal              = $value2->RESET;
            $existe                     = true;
        }
    }
    if ($example['planes'] && $existe) {
        // Insertamos el registro en la base de planes
        guardar_planes($ini_p,$plan, $example['vigenciap'], $example['segmentop'], $example['mercadop'], $abono, $precio, $preciofinal, $CreditoDisponibleFinal, $OFF30HP, $OFF30HPFinal, $MBGBincl, $SegON, $SegOFF, $SMS, $DatosexcFinal, 'planes');
        $ini_p++;
    }
    if ($example['phistoricos'] && $existe) {
    //     // Insertamos el registro en la base de planeshistoricos
        guardar_planes($ini_ph,$plan, $example['vigenciaph'], $example['segmentoph'], $example['mercadoph'], $abono, $precio, $preciofinal, $CreditoDisponibleFinal, $OFF30HP, $OFF30HPFinal, $MBGBincl, $SegON, $SegOFF, $SMS, $DatosexcFinal, 'planeshistoricos');
        $ini_ph++;
    }
}






?>