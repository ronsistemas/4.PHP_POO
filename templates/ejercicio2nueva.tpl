{if isset($opcion) and $opcion!=null}    
    {$id=$opcion->getId()}
    {$opcion_texto=$opcion->getTextoOpcion()}
    {$correcta=$opcion->getCorrecta()}   
    {$nueva=false}
{else}    
    {$id=''}
    {$opcion_texto=''}
    {$correcta=false}   
    {$nueva=true}
{/if}
<!DOCTYPE html>
<HTML>
    <HEAD>
        <title>
            Ejercicio 2. Añadir una opción a la pregunta 99999.
        </title>
    </HEAD>
    <body>
        {if count($lastOpResult)>0  }
            <H2>Resultado de la última operación:</H2>
        <UL>
            {foreach $lastOpResult as $result}
            <LI>{$result}</LI>       
            {/foreach}
        </UL>
        {/if}
       <form action="" method="post">    
            {if !$nueva}<H1>Modificando la opción {$id} </H1> {/if}
            <LABEL>Introduce el texto de la opción:
                <input type="text" name="texto_opcion" value="{$opcion_texto}">
            </LABEL>
            <LABEL>¿Es correcta?
                <input type="checkbox" name="correcta" value="1" {if $correcta}checked{/if}>
            </LABEL>
            <br>
            <br>            
            <button type="submit" name="btn">{if $nueva}¡Crear opción!{else}¡Modificar opción!{/if}</button>
            {if !$nueva}<button type="submit" name="btn_forget">Vaciar sesión.</BUTTON> | 
                 <a href='ejercicio2rescate.php'> Ir a Ejercicio 2 Rescate para ver </a> | 
                 <a href='ejercicio2borrado.php'> Ir a Ejercicio 2 Borrado para borrar </a>
            {/if}
        </form>

    </body>
    
</HTML>
