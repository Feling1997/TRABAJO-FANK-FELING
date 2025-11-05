function validarFormulario(){
    let respuesta = true;
    const titulo = document.querySelector('[name="titulo"]').value.trim();
    const autor = document.querySelector('[name="autor"]').value.trim();
    const isbn = document.querySelector('[name="isbn"]').value.trim();

    if(titulo=="" || autor=="" || isbn==""){
        alert("⚠️ Los campos Título, Autor y ISBN son obligatorios");
        respuesta = false;
    }
    return respuesta;
}

function confirmarEliminacion(){
    return confirm('¿Seguro que deseas eliminar este libro?');
}

function ocultarMensaje(){
    const mensaje = document.getElementById("mensaje");
    if(mensaje){
        setTimeout(() => {
        mensaje.classList.add("fade");
        setTimeout (() => mensaje.remove(), 1000);
        }, 3000);
    }
    return;
}
window.onload = ocultarMensaje;