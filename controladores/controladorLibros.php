<?php
include_once("./config/base_datos.php");

class ControladorLibros{
    public static function obtenerLibros($conexion){
        $consulta="SELECT * FROM libros";
        $resultado=$conexion->query($consulta);
        return $resultado;
    }

    public static function agregarLibro($conexion,$titulo,$autor,$anio,$genero,$stock){
        $consulta="INSERT INTO libros (titulo, autor, anio, genero, stock) VALUES ('$titulo','$autor','$anio','$genero','$stock')";
        return $conexion->query($consulta);
    }

    public static function eliminarLibro($conexion,$id){
        $consulta="DELETE FROM libros WHERE id=$id";
        return $conexion->query($consulta);
    }

    public static function editarLibro($conexion,$id,$titulo,$autor,$anio,$genero,$stock){
        $consulta="UPDATE libros SET titulo='$titulo', autor='$autor', anio_publicacion='$anio', genero='$genero', stock='$stock' WHERE id=$id";
        return $conexion->query($consulta);
    }
}
?>