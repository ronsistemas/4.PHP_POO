<?php

abstract class Pregunta implements IGuardable{
    
    protected $id;
    protected $enunciado;
           
    public function __construct ($enunciado){
        $this->setEnunciado($enunciado);
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getEnunciado()
    {
        return $this->enunciado;
    }
    
    public function setEnunciado($enunciado)
    {        
        $this->enunciado=$enunciado;
    }
      
    public abstract function tipoPregunta();
    
    public function guardar (PDO $PDOconn)
    {
        $result=0;
        $values['enunciado']=$this->enunciado;
        $values['tipo_pregunta']= $this->tipoPregunta();
        if ($this->id===null)
        {
            $query="INSERT INTO pregunta (enunciado,tipo_pregunta) VALUES "
                    . "(:enunciado,:tipo_pregunta)";                                           
        }
        else
        {
            $query="UPDATE pregunta SET enunciado=:enunciado, "
                    . "tipo_pregunta=:tipo_pregunta WHERE id=:id";
            $values['id']=$this->id;
        }
        try {
            $stat=$PDOconn->prepare($query);
            if ($stat->execute($values))
            {
                $result=$stat->rowCount();                 
                if ( $result>0 && $this->id===null) {                    
                    $this->id=$PDOconn->lastInsertId();                    
                }               
            }
        } catch (PDOException $ex) {
            
            //TODO: Registrar un evento con monolog.
            
        }
        return $result;
    }
          
    public static function borrar (PDO $PDOconn, $id)/*:Opcion */
    {
        $result=0;        
        $values['id']=$id;
        
        /*Nota: no es necesario que en tipo_pregunta se ponga el tipo.
         La sentencia podía ser DELETE from pregunta WHERE id=:id 
         sin incluir la parte de tipo_pregunta.
        */ 
        $r=new static();
        $values['tipo_pregunta']=$r->tipoPregunta();
        unset($r);       
        $query="DELETE from pregunta WHERE id=:id and tipo_pregunta=:tipo_pregunta";
        
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

