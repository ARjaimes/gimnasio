<?php
require_once ("../../db/conexion.php"); 
$db = new Database();
$con = $db ->conectar();
session_start();

function getStatus($imc) {
    if ($imc < 18.5) {
        return array('Bajo peso', 'blue');
    } elseif ($imc >= 18.5 && $imc < 25) {
        return array('Peso saludable', 'green');
    } elseif ($imc >= 25 && $imc < 30) {
        return array('Sobrepeso', 'orange');
    } elseif ($imc >= 30 && $imc < 35) {
        return array('Obesidad grado 1', 'red');
    } elseif ($imc >= 35 && $imc < 40) {
        return array('Obesidad grado 2', 'darkred');
    } else {
        return array('Obesidad grado 3', 'purple');
    }
}

//consulta de tablas para ejecucion y recibe el boton del formulario por $_GET
$usua = $con->prepare("SELECT * FROM usuario, rol WHERE documento ='".$_GET['documento']."'AND rol.id_rol = usuario.id_rol");
$usua -> execute ();
$asignae = $usua -> fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ES">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
        <style>
            body {
                font-size: 18px;
                background-color: #222;
                color: #fff;
            }

            .img-center {
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <table class="table table-light">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Rol</th>
                    <th>Peso</th>
                    <th>Estatura</th>
                    <th>I.M.C</th>
                    <th>Estado de peso</th> <!-- Nuevo campo -->
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($asignae as $usua){
                    // Cálculo del IMC
                    $pes = $usua['peso'];
                    $estat = $usua['estatura'] / 100; // Convertir altura a metros
                    $imc = $pes / ($estat * $estat);

                    // Obtener estado y color correspondiente
                    list($status, $color) = getStatus($imc);
                    ?> 
                    <tr>
                        <td> <?=$usua["nombres"]?>  </td>
                        <td> <?=$usua["documento"]?>  </td>
                        <td> <?=$usua["rol"]?>  </td>
                        <td> <?=$usua["peso"]?>  </td>
                        <td> <?=$usua["estatura"]?>  </td>
                        <td style="color: <?=$color?>;"> <?=round($imc, 2)?>  </td>
                        <td> <?=$status?>  </td> <!-- Nuevo campo -->
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <div class="text-center">
            <img class="img-fluid img-center" src="../../images/imc.jpeg" alt=""> 
        </div>
        <div class="text-center mt-3">
            <a href="usuarios.php" class="btn btn-primary">Volver al listado</a>
            <a href="index.php" class="btn btn-secondary">Volver al Menú</a>
        </div>
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
