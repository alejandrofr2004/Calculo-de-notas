<?php
declare(strict_types=1);

$data = [];

if (!empty($_POST)) {
    $data['errores'] = checkErrors($_POST['json']);
    $data['input']['json'] = filter_var($_POST['json'], FILTER_SANITIZE_SPECIAL_CHARS);
    $json = json_decode($_POST['json'], true);
    if (!empty($data['errores'])) {

    }else{
        $datosAsignatura=[];
        foreach ($json as $asignaturas=>$alumnos) {
            $todaslasNotas=[];
            foreach ($alumnos as $alumno=>$notas) {
                foreach ($notas as $nota) {
                    $todaslasNotas[]=$nota;
                }
                $sumaDeNotas=array_sum($todaslasNotas);
            }
            $datosAsignatura['sumaDeNotas']=$sumaDeNotas;
            $resultado[$asignaturas]=$datosAsignatura;
        }
        var_dump($resultado);
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
                $errores['json'][] = 'El json debe tener un array con las materias';
            }else{
                foreach ($json as $asignatura=>$alumnos) {
                    if(!is_string($asignatura)){
                        $errores['json'][] = 'La asignatura '.$asignatura .' no es un nombre válido';
                    }else{
                        if(!is_array($alumnos)){
                            $errores['json'][] = 'La asignatura '.$asignatura .' no contiene un array de alumnos';
                        }
                    }
                    foreach ($alumnos as $alumno=>$notas) {
                        foreach ($notas as $nota) {
                            if(!is_numeric($nota)){
                                $errores['json'][] = 'La nota '.$nota.' del alumno '.$alumno.' de la
                                 asignatura '.$asignatura.' no es un numero.';
                            }
                        }
                    }
                }
            }
        }
    }
    return $errores;
}

include 'views/templates/header.php';
include 'views/calcularNotas.view.php';
include 'views/templates/footer.php';