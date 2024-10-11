<?php
declare(strict_types=1);

$data = [];

if (!empty($_POST)) {
    $data['errores'] = checkErrors($_POST['json']);
    $data['input']['json'] = filter_var($_POST['json'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (empty($data['errores'])) {

    }
}
function checkErrors(string $texto) : array
{
    $errores = [];
    if(empty($texto))
    {
        $errores['json'][] = 'Inserte un json a analizar';
    }
    else{
        $json = json_decode($texto, true);
        if(is_null($json))
        {
            $errores['json'][] = 'El texto introducido no es un JSON bien formado';
        }
        else
        {
            if(!is_array($json)){
                $errores['json'][] = 'El Json no contiene un array de materias';
            }
            else{

            }
        }
    }
    var_dump($errores);
    return $errores;
}

include 'views/templates/header.php';
include 'views/calcularNotas.view.php';
include 'views/templates/footer.php';