<?php
session_start();
include("conexion.php");

$numeroMesa = isset($_POST['mesa']) ? $_POST['mesa'] : '';
$menu = isset($_POST['menu']) ? $_POST['menu'] : '';

// 1. Aplicamos tu lógica original para definir si lleva postre o no
if (isset($_POST["incluyePostre"]) && $_POST["incluyePostre"] == "si") {
    $postre = isset($_POST['postre']) ? $_POST['postre'] : 'Sin especificar';
} else {
    $postre = "No";
}

$idEmpleado = isset($_SESSION['id_empleado']) ? $_SESSION['id_empleado'] : 1;

if (!empty($numeroMesa) && !empty($menu)) {
    try {
        $conexion->beginTransaction();

        // 2. Actualizar estado de la mesa a 'Ocupado'
        $sqlUpdateMesa = "UPDATE mesas SET estado = 'Ocupado' WHERE numero_mesa = ?";
        $stmtMesa = $conexion->prepare($sqlUpdateMesa);
        $stmtMesa->execute([$numeroMesa]);

        $sqlBuscaMesa = "SELECT id_mesa FROM mesas WHERE numero_mesa = ?";
        $stmtBuscaMesa = $conexion->prepare($sqlBuscaMesa);
        $stmtBuscaMesa->execute([$numeroMesa]);
        $mesaData = $stmtBuscaMesa->fetch(PDO::FETCH_ASSOC);
        
        if ($mesaData) {
            $idMesaFK = $mesaData['id_mesa'];
        } else {
            $sqlInsMesa = "INSERT INTO mesas (numero_mesa, estado) VALUES (?, 'Ocupado')";
            $stmtInsMesa = $conexion->prepare($sqlInsMesa);
            $stmtInsMesa->execute([$numeroMesa]);
            $idMesaFK = $conexion->lastInsertId();
        }

        // 3. Registrar el cliente con nombre "Cliente General"
        $sqlCliente = "INSERT INTO clientes (nombre, fecha_registro) VALUES ('Cliente General', NOW())";
        $conexion->query($sqlCliente);
        $idCliente = $conexion->lastInsertId();

        // 4. Guardar el pedido con el postre evaluado correctamente ("No" o el nombre del postre)
        $sqlPedido = "INSERT INTO pedidos (id_cliente, id_mesa, id_empleado, menu, postre, fecha) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmtPedido = $conexion->prepare($sqlPedido);
        $stmtPedido->execute([$idCliente, $idMesaFK, $idEmpleado, $menu, $postre]);

        $conexion->commit();

        echo json_encode(["status" => "success", "message" => "¡Pedido registrado con éxito!"]);

    } catch (Exception $e) {
        $conexion->rollBack();
        echo json_encode(["status" => "error", "message" => "Error al guardar el pedido: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios."]);
}
?>