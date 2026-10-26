<?php

include 'conexion.php';

// Capturamos los datos enviados por el formulario
$mesa = $_POST["mesa"];
$menu = $_POST["menu"];

// Verificamos si la opción elegida fue "si"
if (isset($_POST["incluyePostre"]) && $_POST["incluyePostre"] == "si") {
    // Si eligió Sí, guardamos el postre del desplegable
    $postre = isset($_POST["postre"]) ? $_POST["postre"] : "Sin especificar";
} else {
    // Si eligió No, guardamos la palabra "No" como texto
    $postre = "No";
}

// Inserción en la base de datos
$insertar = "INSERT INTO clientes (Mesa, Menu, Postre) VALUES ('$mesa', '$menu', '$postre')";

$resultado = mysqli_query($conexion, $insertar);

// Si falla, ahora te mostrará el error REAL de MySQL para saber exactamente qué pasó
if (!$resultado) {
    echo "Error al registrar pedido: " . mysqli_error($conexion);
} else {
    echo "¡Pedido registrado con éxito!";
}

mysqli_close($conexion);
?>