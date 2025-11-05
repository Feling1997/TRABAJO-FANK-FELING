<?php
include_once("./config/base_datos.php");

class ControladorLibros{
    public static function obtenerLibros($conexion){
        $consulta="SELECT * FROM libros ORDER BY id DESC";
        $resultado=$conexion->query($consulta);
        return $resultado;
    }

    public static function agregarLibro($conexion,$titulo,$autor,$anio,$genero,$stock, $isbn=null, $editorial=null, $categoria=null, $descripcion=null){
        $mensaje="";
        //verificar que no exista un libro con ese isbn
        if($isbn!=null && $isbn!=""){
            $check=$conexion->prepare("SELECT id FROM libros WHERE isbn=? LIMIT 1");
            $check->bind_param("s",$isbn);
            $check->execute();
            $resultado=$check->get_result();
            if($resultado->num_rows>0)
                $mensaje= "Ya existe un libro con ese isbn";
        }
        if($mensaje==""){
            $consulta="INSERT INTO libros (titulo, autor, anio_publicacion, genero, stock, isbn, editorial, categoria, descripcion, estado) 
                VALUES (?,?,?,?,?,?,?,?,?,'Disponible')";
            $preparacion=$conexion->prepare($consulta);
            $preparacion->bind_param("ssisiisss",$titulo,$autor,$anio,$genero,$stock,$isbn,$editorial,$categoria,$descripcion);

            if($preparacion->execute())
                $mensje="Ok";
            else
                $mensaje="Error al insertar el libro".$preparacion->error;
        }
        return $mensaje;
    }

    public static function editarLibro($conexion,$id,$titulo,$autor,$anio,$genero,$stock, $isbn=null, $editorial=null, $categoria=null, $descripcion=null, $estado='Disponible'){
        $mensaje="";

        //verificar que no exista un libro con ese isbn
        if($isbn!=null && $isbn!=""){
            $check=$conexion->prepare("SELECT id FROM libros WHERE isbn=? LIMIT 1");
            $check->bind_param("si",$isbn,$id);
            $check->execute();
            $resultado=$check->get_result();

            if($resultado->num_rows>0)
                $mensaje= "Ya existe un libro con ese isbn";
        }
        if($mensaje==""){
            $consulta="UPDATE libros SET titulo=?, autor=?, anio_publicacion=?, genero=?, stock=?, isbn=?, editorial=?, categoria=?, descripcion=?, estado=? WHERE id=?";
            $preparacion=$conexion->prepare($consulta);
            $preparacion->bind_param("ssisiissssi",$titulo,$autor,$anio,$genero,$stock,$isbn,$editorial,$categoria,$descripcion,$estado,$id);
            
            if($preparacion->execute())
                $mensje="Ok";
            else
                $mensaje="Error al editar el libro".$preparacion->error;
        }
        return $mensaje;   
    }

    public static function eliminarLibro($conexion,$id){
        $mensaje="";

        $check=$conexion->prepare("SELECT id FROM libros WHERE id=?");  
        $check->bind_param("i",$id);
        $check->execute();
        $resultado=$check->get_result();

        if($resultado->num_rows>0)
            $mensaje= "El libro no puede ser eliminado porque tiene prestamos pendientes";

        if($mensaje==""){
            $preparacion=$conexion->prepare("DELETE FROM libros WHERE id=?");
            $preparacion->bind_param("i",$id);

            if($preparacion->execute())
                $mensje="Ok";
            else
                $mensaje="Error al eliminar el libro".$preparacion->error;
        }
        return $mensaje;
    }
}
?>