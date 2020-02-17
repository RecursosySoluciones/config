<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_POST['nombre'])){
    echo "ERROR_NOMBRE_NO_RECIBIDO";
    die();
}

const separador_csv = ";"; //CAMBIAME PARA SUBIR UN CSV DELIMITADO POR OTRA COSA

require_once('../connections/functions.php'); //CONECTA A LA DB
create_connection();
$nombre = mysqli_real_escape_string( $GLOBALS['conn'],$_POST['nombre']);
$GLOBALS['conn']->close();
$nombre  = strtolower($nombre);
$nombre  = preg_replace('/ /','_',$nombre);
$nombre  = preg_replace('-//','_',$nombre);
if(is_numeric(substr($nombre , 1))){
    echo "El nombre de una tabulacion no puede comenzar con un numero";
    die();
} 

$file_uploaded       = $_FILES['file']['tmp_name'];

$file_uploaded_array = read_file($file_uploaded);

$consulta = "CREATE TABLE tabulaciones_{$nombre} ( ".
    " id_tabulacion int NOT NULL AUTO_INCREMENT," .
    " nivel_1 varchar(255)," .
    " nivel_2 varchar(255)," .
    " nivel_3 varchar(255)," .
    " nivel_4 varchar(255)," .
    " descripcion_1 text," .
    " descripcion_2 text," .
    " descripcion_3 text," .
    " descripcion_4 text," .
    " PRIMARY KEY (id_tabulacion))";

$respuesta = hacer_consulta($consulta); 

$consulta = "INSERT INTO tabulaciones_{$nombre} ".
            "(nivel_1, nivel_2, nivel_3, nivel_4,".
            " descripcion_1, descripcion_2, descripcion_3,".
            " descripcion_4) VALUES ";

foreach ($file_uploaded_array as $key => $line) {
    $string_to_push = "('{$line['NIVEL 1']}',
                        '{$line['NIVEL 2']}',
                        '{$line['NIVEL 3']}',
                        '{$line['NIVEL 4']}',
                        '{$line['DESCRIPCION1']}',
                        '{$line['DESCRIPCION2']}',
                        '{$line['DESCRIPCION3']}',
                        '{$line['DESCRIPCION4']}')";
    $coma = ',';
    $consulta .= $string_to_push;
    if(count($file_uploaded_array) > $key){
        $consulta .= $coma;
    }
}
$consulta = substr($consulta, 0, -1);

$respuesta = hacer_consulta($consulta); 

print_r("Arbol de tabulaciones agregado correctamente!"); 

function read_file($file){
   
    $aux = array();
    
    $handler = fopen($file, 'r');

    $first_line = true;
    
    while (($row = fgetcsv($handler ,1000, separador_csv)) !== FALSE ){
        if($first_line){
            $headers = $row;
            foreach($headers as $key => $value){
                $headers[$key]=strtoupper($value);
            }
            $first_line = false;
        }else{
            $row_aux = array(
                "NIVEL 2"=>"",
                "NIVEL 3"=>"",
                "NIVEL 4"=>"",
                "DESCRIPCION1"=>"",
                "DESCRIPCION2"=>"",
                "DESCRIPCION3"=>"",
                "DESCRIPCION4"=>""                
            );
            foreach($row as $key => $value){
                $row_aux[$headers[$key]] = utf8_encode($value); 
                $row_aux  = preg_replace('/[^a-zA-Z0-9_ -]/s','',$row_aux);
            }
            $aux[] = $row_aux;
        }
    }

    fclose($handler);
    
    return $aux;
}

?>