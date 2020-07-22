<?php



if (file_exists("datos.txt")) {
    $jsonCliente = file_get_contents("datos.txt");
    $aClientes = json_decode($jsonCliente, true);
} else {
    $aClientes[] = "";
}
$id = isset($_GET["id"]) ? $_GET["id"] : "";
$aMsj=array("mensaje"=>"","codigo"=>"");


if (isset($_GET["do"]) && isset($_GET["id"]) && $_GET["do"] == "eliminar") {
    if ($aClientes[$id]['imagen']) {
        $imagen = $aClientes[$id]['imagen'];
        unlink("file/$imagen");
    }
    unset($aClientes[$id]); // funcion que para eliminar array
    $jsonaCliente = json_encode($aClientes);
    file_put_contents("datos.txt", $jsonaCliente);
    $id = "";
    $aMsj=array("mensaje"=>"Cliente Eliminado","codigo"=>"danger");
    header("refresh:3; url=index.php");
}

if ($_POST) {

    $dni = trim($_POST["txtDni"]); //trim eliminar espacio
    $nombre = trim($_POST["txtNombre"]);
    $telefono = trim($_POST["txtTelefono"]);
    $correo = trim($_POST["txtCorreo"]);
    $nombreImagen = "";


    if ($_FILES["archivo"]["error"] === UPLOAD_ERR_OK) {
        $nombreAleatorio = date("Ymdhmsi");
        $archivo_tmp = $_FILES["archivo"]["tmp_name"];
        $nombreArchivo = $_FILES["archivo"]["name"];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nombreImagen = $nombreAleatorio . "." . $extension;
        move_uploaded_file($archivo_tmp, "file/$nombreImagen");
      
    }

    if (isset($_GET['id'])) {
        $imagen = $aClientes[$id]['imagen'];

        if ($_FILES["archivo"]["error"] === UPLOAD_ERR_OK) {
            if ($imagen != "") {
                unlink("file/$imagen");
            }
        }
        if ($_FILES["archivo"]["error"] != UPLOAD_ERR_OK) {
            $nombreImagen = $imagen;
        }
        //actualizacion
        $aClientes[$id] = array(
            "dni" => $dni,
            "nombre" => $nombre,
            "telefono" => $telefono,
            "correo" => $correo,
            "imagen" => $nombreImagen
        );
        if ($aClientes[$id] != "") {
            $aMsj=array("mensaje"=>"Cliente editado","codigo"=>"primary");
            header("refresh:3; url=index.php");
        }
    } else {
        //insercion por primera vez
        $aClientes[] = array(
            "dni" => $dni,
            "nombre" => $nombre,
            "telefono" => $telefono,
            "correo" => $correo,
            "imagen" => $nombreImagen
        );
        if ($aClientes != "") {
            $aMsj=array("mensaje"=>"Cliente Cargado","codigo"=>"success");
            header("refresh:3; url=index.php");
        }
    }
    $jsonaCliente = json_encode($aClientes);
    file_put_contents("datos.txt", $jsonaCliente);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABM Cliente</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap">
    <link rel="stylesheet" href="css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,200,300,400,500,600,700,800,900&display=swap">
    <link rel="stylesheet" href="css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="css/estilo.css">



</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-center py-3">
                <h1>Registro de Clientes</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php if($aMsj !=""):?>
                <div class="alert alert-<?php echo $aMsj["codigo"];?>" role="alert">
                    <?php echo $aMsj["mensaje"];?>
                </div>
                <?php endif;?>
            </div>    
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="txtDni">DNI:</label>
                            <input type="txtText" name="txtDni" class="form-control" id="txtDni" required value="<?php echo isset($aClientes[$id]) ? $aClientes[$id]["dni"] : ""; ?>">
                        </div>
                        <div class="col-12 form-group">
                            <label for="Nombre">Nombre:</label>
                            <input type="text" name="txtNombre" class="form-control" id="txtNombre" required value="<?php echo isset($aClientes[$id]) ? $aClientes[$id]["nombre"] : ""; ?>">
                        </div>
                        <div class="col-12 form-group">
                            <label for="txtTelefono">Telefono:</label>
                            <input type="text" id="txtTelefono" class="form-control" name="txtTelefono" required value="<?php echo isset($aClientes[$id]) ? $aClientes[$id]["telefono"] : ""; ?>">
                        </div>
                        <div class="col-12 form-group">
                            <label for="txtCorreo">Correo:</label>
                            <input type="email" id="txtCorreo" name="txtCorreo" class="form-control" required value="<?php echo isset($aClientes[$id]) ? $aClientes[$id]["correo"] : ""; ?>">
                        </div>
                        <div class="col-12  form-group">
                            <label for="txtFile">Archivo adjunto:</label>
                            <input type="file" class="form-control-file " id="archivo" name="archivo" require>
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" id="btnGuardar" name="btnGuardar">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 col-sm-6 mt-4 ">
                <table class="table table-holver border">
                    <tr>
                        <th>Imagen</th>
                        <th>Dni</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>

                    <?php foreach ($aClientes as $id => $cliente) : ?>
                        <tr>
                            <td><img src="file/<?php echo $cliente["imagen"]; ?>" class="img-thumbnail"></td>
                            <td><?php echo $cliente["dni"]; ?></td>
                            <td><?php echo $cliente["nombre"]; ?></td>
                            <td><?php echo $cliente["correo"]; ?></td>
                            <td style="width: 116px;">
                                <a href="index.php?id=<?php echo $id ?>"><i class="fas fa-edit"></i></a>
                                <a href="index.php?id=<?php echo $id ?>&do=eliminar"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </table>
                <a href="index.php"><i class="fas fa-plus"></i></a>
            </div>
        </div>


    </div>
</body>

</html>