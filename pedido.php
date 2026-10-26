<?php

include 'conexion.php';

$mesa = $_POST["mesa"];
$menu = $_POST["menu"];

if (isset($_POST["incluyePostre"]) && $_POST["incluyePostre"] == "si") {
    $postre = isset($_POST["postre"]) ? $_POST["postre"] : "Sin especificar";
} else {
    $postre = "No";
}

$insertar = "INSERT INTO clientes (Mesa, Menu, Postre) VALUES ('$mesa', '$menu', '$postre')";

$resultado = mysqli_query($conexion, $insertar);

if (!$resultado) {
    echo "Error al registrar pedido: " . mysqli_error($conexion);
} else {
    echo "¡Pedido registrado con éxito!";
}

mysqli_close($conexion);
?>