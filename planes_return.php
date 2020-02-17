<?php

$json = json_decode(file_get_contents('planes_all.json'));

$planes = [
    'Vigente'   => [],
    'NoVigente' => [],
    'Otros'     => []
];

foreach($json as $value){
    if($value->Vigencia == 'Vigente'){
        array_push($planes['Vigente'],$value->Plan);
    }elseif($value->Vigencia == 'No Vigente'){
        array_push($planes['NoVigente'],$value->Plan);

    }else{
        $a = array(
            'plan' => $value->Plan,
            'vigencia' => $value->Vigencia
        );
        array_push($planes['Otros'],$a);
    }
}

$data = json_encode($planes,JSON_PRETTY_PRINT);

if(!file_exists('resultado.json')){
    file_put_contents('resultados.json',$data);
}else{
    die('Elimine el archivo resultados.json');
}


?>