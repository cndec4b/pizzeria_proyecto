function mostrarPostres() {
    const selectorPostre = document.getElementById("selectorPostre");
    if (selectorPostre) {
        selectorPostre.style.display = "block";
    }
}

function ocultarPostres() {
    const selectorPostre = document.getElementById("selectorPostre");
    if (selectorPostre) {
        selectorPostre.style.display = "none";
    }
}

document.addEventListener("DOMContentLoaded", function() {
    // ==========================================
    // 1. GESTIÓN DE SESIÓN DE EMPLEADOS
    // ==========================================
    const loginForm = document.getElementById('login-form');
    const loginScreen = document.getElementById('login-screen');
    const appScreen = document.getElementById('app-screen');
    const loginError = document.getElementById('login-error');

    // Si ya inició sesión antes o tras enviar un pedido, ocultamos el login y mostramos la app
    if (sessionStorage.getItem('isLoggedIn') === 'true') {
        if (loginScreen) loginScreen.style.display = 'none';
        if (appScreen) appScreen.style.display = 'block';
    }

    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const inputEmail = document.getElementById('email').value.trim();
            const inputPassword = document.getElementById('password').value.trim();

            const datos = new FormData();
            datos.append('correo', inputEmail);
            datos.append('password', inputPassword);

            fetch('login_empleados.php', {
                method: 'POST',
                body: datos
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Guardamos la sesión para que sobreviva al reload del pedido
                    sessionStorage.setItem('isLoggedIn', 'true');
                    
                    if (loginScreen) loginScreen.style.display = 'none';
                    if (appScreen) appScreen.style.display = 'block';
                    if (loginError) loginError.style.display = 'none';
                } else {
                    if (loginError) {
                        loginError.textContent = data.message;
                        loginError.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error de conexión con la base de datos.');
            });
        });
    }

    // ==========================================
    // 2. PINTAR MESAS OCUPADAS (Tu código original)
    // ==========================================
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

// ==========================================
// 3. ENVÍO DE PEDIDOS (Tu código original)
// ==========================================
const formularioPedido = document.getElementById("formularioPedido");
if (formularioPedido) {
    formularioPedido.addEventListener("submit", function(event) {
        event.preventDefault(); // Evita que la página recargue de golpe

        // Asegúrate de que el ID coincida con tu HTML ("selectMesa" o "selectmesa")
        const selectMesa = document.getElementById("selectMesa") || document.getElementById("selectmesa");
        const numeroMesaSeleccionada = selectMesa ? selectMesa.value.trim() : "";

        const datos = new FormData(this);

        fetch("pedido.php", {
            method: "POST",
            body: datos
        })
        .then(response => response.text())
        .then(resultado => {
            console.log(resultado);

            let mesasOcupadas = JSON.parse(localStorage.getItem("mesasOcupadas")) || [];
            if (numeroMesaSeleccionada && !mesasOcupadas.includes(numeroMesaSeleccionada)) {
                mesasOcupadas.push(numeroMesaSeleccionada);
                localStorage.setItem("mesasOcupadas", JSON.stringify(mesasOcupadas));
            }

            alert("¡Pedido registrado con éxito!");

            // Al recargar, sessionStorage mantiene la sesión activa y se pintarán las mesas en rojo
            location.reload();
        })
        .catch(error => {
            alert("Hubo un error al enviar el pedido.");
            console.log(error);
        });
    });
}

// ==========================================
// 4. RESETEAR MESAS
// ==========================================
const btnReset = document.getElementById("btnReset");
if (btnReset) {
    btnReset.addEventListener("click", function() {
        localStorage.clear();
        location.reload();
    });
}