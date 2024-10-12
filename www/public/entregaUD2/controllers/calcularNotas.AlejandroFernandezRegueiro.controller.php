<?php
declare(strict_types=1);

$data = [];

if (!empty($_POST)) {
    $data['errores'] = checkErrors($_POST['json']);
    $data['input']['json'] = filter_var($_POST['json'], FILTER_SANITIZE_SPECIAL_CHARS);
    $json = json_decode($_POST['json'], true);
    if (empty($data['errores'])) {
        $datosAsignatura=[];
        $contarSuspensosAlumnos=[];
        foreach ($json as $asignaturas=>$alumnos) {
            $todaslasNotas=[];
            $aprobados=0;
            $suspensos=0;
            $notaMasAlta=-1;
            $notaMasBaja=11;
            $alumnoNotaMasAlta="";
            $alumnoNotaMasBaja="";
            foreach ($alumnos as $alumno=>$notas) {
                foreach ($notas as $nota) {
                    if(!isset($contarSuspensosAlumnos[$alumno])) {
                        $contarSuspensosAlumnos[$alumno]=0;
                    }
                    $todaslasNotas[]=$nota;
                    if($nota>=5){
                        $aprobados++;
                    }else{
                        $contarSuspensosAlumnos[$alumno]++;
                        $suspensos++;
                    }
                    if($nota>$notaMasAlta){
                        $notaMasAlta=$nota;
                        $alumnoNotaMasAlta=$alumno;
                    }elseif ($nota<$notaMasBaja){
                        $notaMasBaja=$nota;
                        $alumnoNotaMasBaja=$alumno;
                    }
                }
                $sumaDeNotas=array_sum($todaslasNotas);
            }
            $datosAsignatura['media']=!empty($alumnos)?round($sumaDeNotas/count($todaslasNotas),2):"-";
            $datosAsignatura['aprobados']=$aprobados;
            $datosAsignatura['suspensos']=$suspensos;
            $datosAsignatura['notaMasAlta']=!empty($alumnos)?$alumnoNotaMasAlta.": ".$notaMasAlta:"-";
            $datosAsignatura['notaMasBaja']=!empty($alumnos)?$alumnoNotaMasBaja.": ".$notaMasBaja:"-";
            $resultado[$asignaturas]=$datosAsignatura;
        }
        $data['resultado']=$resultado;
        $data['listadoSuspensos']=contarSuspensosAlumnos($contarSuspensosAlumnos);
    }
}

function contarSuspensosAlumnos(array $alumnos):array
{
    $aprobaronTodo=[];
    $suspendieronAlguna=[];
    $promocionan=[];
    $noPromocionan=[];
    foreach ($alumnos as $alumno=>$suspensos) {
        if($suspensos==0){
            $aprobaronTodo[]=$alumno;
            $promocionan[]=$alumno;
        }elseif ($suspensos==1){
            $suspendieronAlguna[]=$alumno;
            $promocionan[]=$alumno;
        }else{
            $suspendieronAlguna[]=$alumno;
            $noPromocionan[]=$alumno;
        }
    }
    return [
        'aprobaronTodo'=>$aprobaronTodo,
        'suspendieronAlguna'=>$suspendieronAlguna,
        'promocionan'=>$promocionan,
        'noPromocionan'=>$noPromocionan
    ];
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
                        } else {
                            foreach ($alumnos as $alumno => $notas) {
                                if (!is_string($alumno)) {
                                    $errores['json'][] = 'El nombre del alumno ' . $alumno . ' de la asignatura
                                     ' . $asignatura . ' no es un nombre valido';
                                } else {
                                    if (!is_array($notas)) {
                                        $errores['json'][] = 'El alumno ' . $alumno . ' no contiene un array de notas';
                                    } else {
                                        foreach ($notas as $nota) {
                                            if (!is_numeric($nota)) {
                                                $errores['json'][] = 'La nota ' . $nota . ' del alumno ' . $alumno . ' de la
                                 asignatura ' . $asignatura . ' no es un numero.';
                                            }
                                        }
                                    }
                                }
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
include 'views/calcularNotas.AlejandroFernandezRegueiro.view.php';
include 'views/templates/footer.php';