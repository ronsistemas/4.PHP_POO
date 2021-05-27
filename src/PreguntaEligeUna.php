<?php

class PreguntaEligeUna extends Pregunta implements IConjuntoOpciones{
    
    private $opciones;
    private $toDelete;    
            
    public function __construct ($enunciado='')
    {
        parent::__construct($enunciado);
        $this->opciones=[];
        $this->toDelete=[];
    }

    public function tipoPregunta() {
        return "EligeUna";
    }

    public static function rescatar(\PDO $PDOconn, $id) {
        
        $ok=false;
        /*
         * Nota: crea una instancia de la implementación actual, 
         * si es una subclase, será de la subclase. Este aspecto no es 
         * necesario realmente, puede solventarse de otra forma.
         */
        
        $ret=new static(); 
        $values['id']=$id;        
        $values['tipo_pregunta']=$ret->tipoPregunta();        
        $query="SELECT id, enunciado from pregunta WHERE id=:id and tipo_pregunta=:tipo_pregunta";
        try {
            $stat=$PDOconn->prepare($query);
            if ($stat->execute($values))
            {                
                $res=$stat->fetch(PDO::FETCH_ASSOC);
                if ($res)
                {                    
                    $ret->setEnunciado($res['enunciado']);
                    $ret->id=$res['id'];
                    $ret->rescatarOpciones($PDOconn);
                    $ok=true;
                } 
            } 
        } catch (PDOException $ex) {
            
            //TODO: Registrar un evento con monolog.

        }
        return $ok?$ret:null;
    }

    private function rescatarOpciones($PDOconn) {        
        $values['idpregunta']=$this->id;
        $query="SELECT id from opcion WHERE idpregunta=:idpregunta";
        try {
            $stat=$PDOconn->prepare($query);
            if ($stat->execute($values))
            {                
                while ($opcionId=$stat->fetch(PDO::FETCH_ASSOC))
                {
                    $this->addOpcion(Opcion::rescatar($PDOconn, $opcionId['id']));
                }                
            }
        } catch (PDOException $ex) {

            //TODO: Registrar un evento con monolog.

        }
    }
    
    public function guardar(\PDO $PDOconn)
    {
        $res=parent::guardar($PDOconn);
        $idPregunta=$this->getId();
        foreach ($this->opciones as $opcion)
        {
            $opcion->setIdPregunta($idPregunta);
            $opcion->guardar($PDOconn);
        }
        foreach ($this->toDelete as $opcion)
        {
            if ($opcion->getId()!=null)
            {
                Opcion::borrar($PDOconn, $opcion->getId());
            }
        }
        $this->toDelete=[];
        return $res;
    }
    
    public function addOpcion($opcion) {
        $this->opciones[]=$opcion;
    }

    public function getOpciones()
    {
        return $this->opciones;
    }
    
    public function delOpcion($n) {
        if (isset($this->opciones[$n]))
        {
            $this->toDelete[]=$this->opciones[$n];
            array_splice($this->opciones,$n,1);
        }
        /*
         TODO: else... registrar el evento con monolog.
         */
    }

}
