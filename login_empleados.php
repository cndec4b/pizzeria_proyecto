<?php
session_start();
include("conexion.php");

$correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (!empty($correo) && !empty($password)) {
    $sql = "SELECT * FROM empleados WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$correo]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($empleado) {
        if ($empleado['contraseña'] === $password) {
            $_SESSION['id_empleado'] = $empleado['id_empleado'];

            echo json_encode(["status" => "success", "message" => "Login exitoso"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Contraseña incorrecta"]);
        }
    } else {
        // Si no existe, lo registramos automáticamente
        $sqlInsert = "INSERT INTO empleados (correo, contraseña) VALUES (?, ?)";
        $stmtInsert = $conexion->prepare($sqlInsert);
        
        if ($stmtInsert->execute([$correo, $password])) {
            $_SESSION['id_empleado'] = $conexion->lastInsertId();
            echo json_encode(["status" => "success", "message" => "Empleado registrado e ingreso exitoso"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al registrar empleado"]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Campos vacíos"]);
}
?>