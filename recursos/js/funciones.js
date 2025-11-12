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

function confirmarEliminacion() {
    return confirm("¿Seguro que deseas eliminar?");
}

document.addEventListener("DOMContentLoaded", function() {
    const rows = document.querySelectorAll("#tablaPrestamos tbody tr");
    rows.forEach((row, i) => {
        row.style.opacity = 0;
        setTimeout(() => {
            row.style.transition = "opacity 0.6s ease";
            row.style.opacity = 1;
        }, 80 * i);
    });
});


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