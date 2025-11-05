<?php
include_once("../config/base_datos.php");

class ControladorPrestamos {

    //Listar prestamos
    public static function obtenerPrestamos($conexion) {
        $consulta = "SELECT p. l.titulo AS libro, u.nombre_completo AS usuario FROM prestamos p
            LEFT JOIN libros l ON p.libro_id = l.id
            LEFT JOIN usuarios u ON p.usuario_id = u.id
            ORDER BY p.id DESC";
        $resultado = $conexion->query($consulta);
        return $resultado;
    }

    public static function agregarPrestamo($conexion, $libro_id, $usuario_id, $fecha_prestamo, $fecha_devolucion, $observaciones=null) {
        $mensaje = "";

        $check=$conexion->prepare("SELECT id FROM prestamos WHERE libro_id = ? AND estado = 'activo' LIMIT 1");
        $check->bind_param("i", $libro_id);
        $check->execute();
        $resultado = $check->get_result();

        if($resultado->num_rows==0)
            $mensaje = "El libro ya está prestado";

        if($mensaje==""){
            $consulta="INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, fecha_devolucion, observaciones, estado) VALUES (?, ?, ?, ?, ?, 'activo')";
            $preparacion=$conexion->prepare($consulta);
            $preparacion->bind_param("iisss", $libro_id, $usuario_id, $fecha_prestamo, $fecha_devolucion, $observaciones);

            if($preparacion->execute()){
                $subir=$conexion->prepare("UPDATE libros SET estado='prestado' WHERE id = ?");
                $subir->bind_param("i", $libro_id);
                $subir->execute();
                $mensaje="OK";
            }
            else
                $mensaje="Error al agregar prestamo".$preparacion->error;
        }
        return $mensaje;
    }

    public static function devolverPrestamo($conexion, $id, $fehca_dev_real){
        $mensaje="";

        $preparacion=$conexion->prepare("SELECT libro_id FROM prestamos WHERE id=? AND estado='activo'");
        $preparacion->bind_param("i", $id);
        $preparacion->execute();
        $resultado=$preparacion->get_result();
        $prestamo=$resultado->fetch_assoc();

        if($prestamo)
            $mensaje="No se encontró el prestamo activo";
        
        if($mensaje==""){
            $subir=$conexion->prepare("UPDATE prestamos SET estado='devuelto', fecha_devolucion=? WHERE id=?");
            $subir->bind_param("si", $fehca_dev_real, $id);

            if($subir->execute()){
                $libro_id=$prestamo["libro_id"];
                $preparacion2=$conexion->prepare("UPDATE libros SET estado='disponible' WHERE id=?");
                $preparacion2->bind_param("i", $libro_id);
                $preparacion2->execute();
                $mensaje="OK";
            }
            else
                $mensaje="Error al devolver prestamo".$preparacion->error;
        }
        return $mensaje;
    }

    public static function eliminarPrestamo($conexion, $id){
        $mensaje="";

        $check=$conexion->prepare("SELECT id FROM prestamos WHERE id = ? AND estado = 'activo'");
        $check->bind_param("i", $id);
        $check->execute();
        $resultado = $check->get_result();

        if($resultado->num_rows==0)
            $mensaje = "No se puede eliminar el prestamo activo";
        else{
            $preparacion=$conexion->prepare("DELETE FROM prestamos WHERE id=?");
            $preparacion->bind_param("i", $id);

            if($preparacion->execute())
                $mensaje="OK";
            else
                $mensaje="Error al eliminar prestamo".$preparacion->error;
        }
        return $mensaje;
    }
}
?>