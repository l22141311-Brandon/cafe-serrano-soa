// Variable global para saber si el usuario está logueado
let usuario = document.getElementById('nombre-usuario-oculto') ? document.getElementById('nombre-usuario-oculto').value : 'invitado';

function agregarAlCarrito(nombre, precio) {
    if (usuario === 'invitado') {
        mostrarNotificacion("¡Hola! Para agregar productos al carrito y comprar, primero debes iniciar sesión.", function() {
            window.location.href = 'login.php';
        });
        return;
    }

    let llaveCarrito = 'carritoCafe_' + usuario;
    let carrito = JSON.parse(localStorage.getItem(llaveCarrito)) || [];
    carrito.push({ nombre: nombre, precio: precio });
    localStorage.setItem(llaveCarrito, JSON.stringify(carrito));
    
    mostrarNotificacion(`${nombre} agregado al carrito 🛒`);
    actualizarContador();
}

function mostrarCarrito() {
    const lista = document.getElementById('lista-carrito');
    const totalElemento = document.getElementById('total-pagar');
    if(!lista) return; 

    let llaveCarrito = 'carritoCafe_' + usuario;
    let carrito = JSON.parse(localStorage.getItem(llaveCarrito)) || [];

    lista.innerHTML = '';
    let total = 0;

    carrito.forEach((producto, index) => {
        const div = document.createElement('div');
        div.className = 'item-carrito';
        div.innerHTML = `
            <p>${producto.nombre} - $${producto.precio.toFixed(2)} 
            <button onclick="eliminarDelCarrito(${index})" style="width:auto; padding:2px 8px; margin-left:10px;">X</button></p>
        `;
        lista.appendChild(div);
        total += producto.precio;
    });

    totalElemento.innerText = `$${total.toFixed(2)}`;
}

function eliminarDelCarrito(index) {
    let llaveCarrito = 'carritoCafe_' + usuario;
    let carritoActual = JSON.parse(localStorage.getItem(llaveCarrito)) || [];
    carritoActual.splice(index, 1);
    localStorage.setItem(llaveCarrito, JSON.stringify(carritoActual));
    mostrarCarrito();
    actualizarContador();
}

function actualizarContador() {
    let llaveCarrito = 'carritoCafe_' + usuario;
    let carritoActual = JSON.parse(localStorage.getItem(llaveCarrito)) || [];
    const contador = document.getElementById('contador-carrito');
    if(contador) contador.innerText = carritoActual.length;
}

function procesarPago() {
    let llaveCarrito = 'carritoCafe_' + usuario;
    let carritoActual = JSON.parse(localStorage.getItem(llaveCarrito)) || [];

    if(carritoActual.length === 0) {
        mostrarNotificacion("Tu carrito está vacío.");
        return;
    }
    
    let total = carritoActual.reduce((sum, item) => sum + item.precio, 0);
    
    fetch('php/guardar_pedido.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ total: total, productos: carritoActual })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            mostrarNotificacion("¡Pago aprobado! Tu pedido está en preparación.", function() {
                localStorage.removeItem(llaveCarrito); 
                window.location.href = 'perfil.php'; 
            });
        } else {
            mostrarNotificacion("Error: " + data.mensaje);
        }
    });
}

window.onload = function() {
    if(document.getElementById('lista-carrito')) {
        mostrarCarrito();
        actualizarContador();
    }
};