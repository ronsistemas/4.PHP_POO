<?php

/**
 * Clase que implementa una opción. 
 */
class Opcion implements IGuardable {
        
    private $idPregunta=null;
    private $textoOpcion;
    private $id=null;
    private $correcta;    
    
    
    public function __construct($textoOpcion='',$correcta=false)
    {        
        $this->textoOpcion=$textoOpcion;
        $this->setCorrecta($correcta);
    }
    
    public function setIdPregunta($idPregunta)
    {
        $this->idPregunta=$idPregunta;
    }
    
    public function getIdPregunta()
    {
        return $this->idPregunta;
    }        
    
    public function getId()
    {
        return $this->id;
    }        
    
    public function getTextoOpcion()
    {
        return $this->textoOpcion;
    }
    
    public function setTextoOpcion($textoOpcion)
    {
        $this->textoOpcion=$textoOpcion;
    }
    
    public function setCorrecta($correcta)
    {
        $this->correcta=(bool)$correcta;
    }
    
    public function getCorrecta()
    {
        return $this->correcta;
    }
    
    public function guardar(PDO $PDOconn) {
        $result = 0;
        $values['texto_opcion'] = trim($this->textoOpcion);
        $values['correcta'] = $this->correcta;
        $lenTO = strlen($values['texto_opcion']);
        //Antes de hacer la inserción verificamos que la longitud 
        //del texto de opción esté entre 2 y 45.
        if ($lenTO >= 2 || $lenTO <= 45) {
            if ($this->id === null) {
                $values['idpregunta'] = $this->idPregunta;
                $query = "INSERT INTO opcion (idpregunta, texto_opcion,correcta) VALUES "
                        . "(:idpregunta, :texto_opcion,:correcta)";
            } else {
                $query = "UPDATE opcion SET texto_opcion=:texto_opcion, "
                        . "correcta=:correcta WHERE id=:id";
                $values['id'] = $this->id;
            }
            try {
                $stat = $PDOconn->prepare($query);
                if ($stat->execute($values)) {
                    if ($this->id === null) {
                        $this->id = $PDOconn->lastInsertId();
                    }
                    $result = $stat->rowCount();
                }
            } catch (PDOException $ex) {

                //TODO: Registrar un evento con monolog.
            }
        }
        return $result;
    }

    public static function rescatar (PDO $PDOconn, $id)/*:Opcion */
    {
        $ret=null;
        $values['id']=$id;
        $query="SELECT id, idpregunta, texto_opcion,correcta from Opcion WHERE id=:id";
        try {
            $stat=$PDOconn->prepare($query);
            if ($stat->execute($values))
            {                
                $res=$stat->fetch(PDO::FETCH_ASSOC);
                if ($res)
                {
                    $ret=new Opcion($res['texto_opcion'],$res['correcta']);
                    $ret->idPregunta=$res['idpregunta'];
                    $ret->id=$res['id'];
                }
            }
        } catch (PDOException $ex) {
            //TODO: Registrar un evento con monolog.
        }
        return $ret;
    }
    
    public static function borrar (PDO $PDOconn, $id)/*:Opcion */
    {
        $result=0;
        $values['id']=$id;
        $query="DELETE from Opcion WHERE id=:id";
        try {
            $stat=$PDOconn->prepare($query);
            if ($stat->execute($values))
            {                
                $result=$stat->rowCount();
            }
        } catch (PDOException $ex) {
            //TODO: Registrar un evento con monolog.
        }
        return $result;
    }
}

