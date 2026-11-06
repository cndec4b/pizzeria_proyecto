<?php
include("conexion.php");
$stmt = $conexion->query("SELECT numero_mesa FROM mesas WHERE estado = 'Ocupado'");
$mesas = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo json_encode($mesas);
?>