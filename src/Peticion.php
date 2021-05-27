<?php

/**
 * Clase que facilita la gestión de parámetros recibidos vía GET y POST. 
 * Ejemplo:
 * 
 * Peticion $p=new Peticion();
 * if ($p->has('a','b')) ... //Comprobar si se han recibido los parámetros 'a' y 'b'
 * 
 * $a=$p->getInt('a'); //Obtener el parámetro 'a' como un entero.
 * 
 */
class Peticion {

    const GET=1;
    const POST=2;
    const BOTH=3;
    
    private $params;
    
    public function __construct($opt=Peticion::BOTH) {
        switch ($opt)
        {
            case Peticion::GET:
                $this->params = $_GET;
            break;
            case Peticion::POST:
                $this->params = $_POST;
            break;
            case Peticion::BOTH:
            default:
                $this->params = array_merge($_POST, $_GET);
            break;                            
        }
            
    }

    /**
     * Función que permite verificar si en la petición HTTP se han 
     * recibido uno o más parámetros. 
     * Uso:
     * Peticion $p=new Peticion();
     * if ($p->has('a','b')) ...
     * 
     * @param mixed $... todos los parámetros a verificar (admite multiples argumentos).
     * @return true si existen todos los parámetros y false en caso contrario.
     * 
     */
    public function has ()
    {        
        $res=true;
        for($i=0;$i<func_num_args() && $res;$i++)
        {
           $res&=isset($this->params[func_get_arg($i)]);
        }
        return $res;
    }        
    
    private function get($name)
    {
        return isset($this->params[$name]) ? $this->params[$name] : null;
    }
        
    
    /**
     * Obtiene un parámetro tipo entero.
     * @param type $paramName nombre del parámetro
     * @return type int
     * @throws Exception Se lanza excepción si no existe el parámetro o si
     * el parámetro no es un entero.
     */
    public function getInt($paramName) {
        
        if (!$this->has($paramName)) 
        {
            throw new Exception("No existe $paramName.");
        }
        
        $value=trim($this->get($paramName));
        
        if (!preg_match('/^[0-9]+/', $value))
        {
            throw new Exception("$paramName no es un número entero.");
        }        
        
        return (int) $value;
    }
    /**
     * Obtiene el parámetro del nombre $paramName y lo procesa para obtener 
     * un tipo de dato double.
     * @param string $paramName Tipo del parámetro
     * @param string $locale Nombre del locale del número decimal.
     * @return type double
     * @throws Exception Se lanza excepción si no existe el parámetro o si
     * el parámetro no es un número.
     */
    public function getDouble($paramName, $locale='es_ES'){
        setlocale(LC_NUMERIC, $locale);
        if (!$this->has($paramName)) 
        {
            throw new Exception("No existe $paramName.");
        }          
        $value=trim($this->get($paramName));
        if (!is_numeric($value))
        {
            throw new Exception("$paramName no es un número con decimales.");
        }
        return floatval($value);
    }
    
    /**
     * Obtiene el parámetro del nombre $name y lo procesa para obtener 
     * un tipo de dato string.
     * @param string $param Tipo del parámetro
     * @param boolean $trim Si es true, se eliminan espacios anteriores y 
     * posteriores.
     * @return type string
     * @throws Exception Se lanza excepción si no existe el parámetro.
     */
    public function getUnsafeString($name, $trim=true) {
    
        if (!$this->has($name)) 
        {
            throw new Exception("No existe $name.");
        } 
        $value = (string) $this->get($name);
        
        if($trim) {
            $value=trim($value);
        }
        
        return $value;    
    }
    
    /**
     * Obtiene un parámetro tipo string añadiendole barras de escape
     * de carácteres especiales (para su uso en consultas SQL).
     * @param string $name Nombre del parámetro a obtener.
     * @param boolean $trim Si se debe o no eliminar espacios antes y después de la cadena.
     * @return string cadena contenida el el parámetro.
     * @throws ParamException si el parámetro no existe.
     */
    public function getString($name, $trim=true)
    {
        return addslashes($this->getUnsafeString($name,$trim));
    }
    
    /**
     * Obtiene un parámetros que se reciben en formato array $param[...] 
     * (normamente usado en checkbox y otros casos).
     * @param string $name nombre del parámetro 
     * @param boolean $trim si se limpia de espacios cada elemento del array
     * @return array[string]
     * @throws Exception LAnza una excepción si no existe el parámetro o 
     * si no es un array.
     */
    public function getArrayOfUnsafeStrings($name,$trim=true) 
    {
        if (!$this->has($name)) 
        {
            throw new Exception("No existe $name.");
        }           
        elseif (!is_array($this->get($name)))
        {
            throw new Exception("No es un array $name.");
        }
        $ret=$this->get($name);
        $trim && array_walk ($ret,function (&$val) {$val=trim($val);});
        return $ret;
    }
    /**
     * Obtiene un parámetros que se reciben en formato array $param[...] 
     * (normamente usado en checkbox y otros casos). A cada elemento del
     * array se le aplica addslashes para escapar las comillas y comillas dobles.
     * 
     * @param string $name nombre del parámetro 
     * @param boolean $trim si se limpia de espacios cada elemento del array
     * @return array[string]
     * @throws Exception LAnza una excepción si no existe el parámetro o 
     * si no es un array.
     */
    public function getArrayOfStrings($name,$trim=true)
    {                
        $r=(array)$this->getArrayOfUnsafeStrings($name,$trim);        
        array_walk ($r,
                function (&$val) {$val= addslashes($val);});        
        return $r;
    }
}