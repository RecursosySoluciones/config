<?php

class DB {
    private $host,$user,$pass,$base;

    public function __construct($host, $user, $pass, $base){
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->base = $base;
    }

    private function connect(){
        $db = new PDO(
            'mysql:host='. $this->host . ';dbname=' . $this->base,
            $this->user,
            $this->pass
        );
        return $db;
    }

    public function query($query){
        $db = $this->connect();
        $result = $db->query($query);
        $resultados = [];
        while($o = $result->fetch()){
            $resultados[] = $o;
        }
        return $resultados;
    }


}


function guardar_planes($id,$Plan,$Vigencia,$Segmento,$Mercado,$Abono,$Precio,$PrecioFinal,$CreditoDisponibleFinal,$OFF30HP,$OFF30HPFinal,$MBGBincl,$SegON,$SegOFF,$SMS,$DatosexcFinal,$table){
    $c = new PDO(
        'mysql:host='. DB_HOST . ';dbname=' . DB_BASE,
        DB_USER,
        DB_PASS
    );
    if($table == 'planes'){
        $query = "INSERT INTO {$table} (id,fecha,Plan,Vigencia,Segmento,Mercado,Abono,Precio,PrecioFinal,MBGBincl,SegON,SegOFF,SMS,ReseteadorFinal) VALUES ({$id},1583463600,'{$Plan}','{$Vigencia}','{$Segmento}','{$Mercado}','{$Abono}','{$Precio}','{$PrecioFinal}','{$MBGBincl}','{$SegON}','{$SegOFF}','{$SMS}','{$DatosexcFinal}');";
        var_dump(ini_p);
    }elseif($table == 'planeshistoricos'){
        $query = "INSERT INTO {$table} (id,fecha,Plan,Vigencia,Segmento,Mercado,Abono,Precio,PrecioFinal,CreditoDisponibleFinal,OFF30HP,OFF30HPFinal,MBGBincl,SegON,SegOFF,SMS,DatosexcFinal) VALUES ({$id},1583463600,'{$Plan}','{$Vigencia}','{$Segmento}','{$Mercado}','{$Abono}','{$Precio}','{$PrecioFinal}','{$CreditoDisponibleFinal}','{$OFF30HP}','{$OFF30HPFinal}','{$MBGBincl}','{$SegON}','{$SegOFF}','{$SMS}','{$DatosexcFinal}');";
    }
    $rta = $c->query($query);
    var_dump($rta);
}

// Con estos datos consultamos a la tabla planes y nos fijamos si esta ahi
function consultar_planes($id,$plan,$fecha,$vigencia){
    $c = new DB(DB_HOST,DB_USER,DB_PASS,DB_BASE);
    $rta1 = $c->query("SELECT * FROM planes WHERE id = {$id} AND Plan = '{$plan}'");
    $rta2 = $c->query("SELECT * FROM planeshistoricos WHERE id = {$id} AND Plan = '{$plan}'");
    $rtn = array(
        'planes' => empty($rta1) ? false : true,
        'phistoricos' => empty($rta2) ? false : true,
        'segmentop' => $rta1[0]['Segmento'],
        'segmentoph' => $rta2[0]['Segmento'],
        'mercadop' => $rta1[0]['Mercado'],
        'mercadoph' => $rta2[0]['Mercado'],
        'vigenciap' => $rta1[0]['Vigencia'],
        'vigenciaph' => $rta2[0]['Vigencia']
    );
    return $rtn;
}

?>
