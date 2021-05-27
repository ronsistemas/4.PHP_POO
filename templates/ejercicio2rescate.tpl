{if isset($opcion) and $opcion!=null}    
    {$id=$opcion->getId()}
    {$idpregunta=$opcion->getIdPregunta()}
    {$opcion_texto=$opcion->getTextoOpcion()}
    {$correcta=$opcion->getCorrecta()}   
{else}    
    {$id=''}    
{/if}
<!DOCTYPE html>
<HTML>
    <HEAD>
        <title>
            Ejercicio 2. Rescatar una opción de la pregunta 99999.
        </title>
    </HEAD>
    <body>
        {if isset($lastOpResult) and count($lastOpResult)>0  }
            <H2>Resultado de la última operación:</H2>
        <UL>
            {foreach $lastOpResult as $result}
            <LI>{$result}</LI>       
            {/foreach}
        </UL>
        {/if}
        {if $id}
           <H2>Mostrando información de la opción {$id} </H2> 
           <P>Texto de la opción:{$opcion_texto}</P>
           <P>¿Es correcta?{if $correcta}SI{else}NO{/if}</P>      
          <form action="" method="post">   
              <button type="submit" name="btn_forget">Vaciar sesión.</button> | 
              <a href='ejercicio2nueva.php'> Ir a Ejercicio 2 Nueva para editar </a> | 
              <a href='ejercicio2borrado.php'> Ir a Ejercicio 2 Borrado para borrar </a>
          </form>            

       {else}
            <H2>Rescatar opción de la pregunta 99999</H2>
            <form action="" method="post">   
                 <LABEL>Id de la pregunta a rescatar:
                    <input type="text" name="idopcion" value="">                    
                </LABEL>
                <button type="submit" name="btn">¡Rescatar!</button>
            </form>
       {/if}
            
        </form>

    </body>
    
</HTML>
