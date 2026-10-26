function mostrarPostres() {
    document.getElementById("selectorPostre").style.display = "block";
}

function ocultarPostres() {
    document.getElementById("selectorPostre").style.display = "none";
}

// 1. Al cargar la página, pintamos de rojo las mesas que estén guardadas en localStorage
document.addEventListener("DOMContentLoaded", function() {
    const mesasOcupadas = JSON.parse(localStorage.getItem("mesasOcupadas")) || [];
    
    const cuadrosMesas = document.querySelectorAll(".columna-mesas .mesa");
    
    cuadrosMesas.forEach(cuadro => {
        const numeroMesa = cuadro.textContent.trim();
        
        if (mesasOcupadas.includes(numeroMesa)) {
            cuadro.classList.remove("disponible");
            cuadro.classList.add("ocupada");
        }
    });
});

// 2. Al hacer clic en "Enviar pedido"
document.getElementById("formularioPedido").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita que la página recargue de golpe

    const selectMesa = document.getElementById("selectmesa");
    const numeroMesaSeleccionada = selectMesa.value.trim();

    const datos = new FormData(this);

    // Enviamos los datos a PHP para que se registren en phpMyAdmin
    fetch("pedido.php", {
        method: "POST",
        body: datos
    })
    .then(response => response.text())
    .then(resultado => {
        console.log(resultado);

        // Guardamos la mesa seleccionada en el localStorage del navegador
        let mesasOcupadas = JSON.parse(localStorage.getItem("mesasOcupadas")) || [];
        if (!mesasOcupadas.includes(numeroMesaSeleccionada)) {
            mesasOcupadas.push(numeroMesaSeleccionada);
            localStorage.setItem("mesasOcupadas", JSON.stringify(mesasOcupadas));
        }

        alert("¡Pedido registrado con éxito!");

        // Recargamos la página automáticamente para ver la mesa en rojo
        location.reload();
    })
    .catch(error => {
        alert("Hubo un error al enviar el pedido.");
        console.log(error);
    });
});

document.getElementById("btnReset").addEventListener("click", function() {
    localStorage.clear();
    location.reload();
});