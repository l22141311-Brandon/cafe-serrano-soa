function mostrarNotificacion(mensaje, callback) {
    let noti = document.getElementById('notificacion-global');
    if (!noti) {
        noti = document.createElement('div');
        noti.id = 'notificacion-global';
        noti.className = 'notificacion-flotante';
        document.body.appendChild(noti);
    }
    
    noti.innerHTML = `<p>${mensaje}</p><button onclick="cerrarNotificacion()">Aceptar</button>`;
    
    setTimeout(() => { noti.classList.add('mostrar'); }, 10);

    window.accionCerrarNotificacion = callback;
}

function cerrarNotificacion() {
    let noti = document.getElementById('notificacion-global');
    if (noti) {
        noti.classList.remove('mostrar');
    }
    if (typeof window.accionCerrarNotificacion === 'function') {
        window.accionCerrarNotificacion();
        window.accionCerrarNotificacion = null;
    }
}