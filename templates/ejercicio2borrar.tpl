<!DOCTYPE html>
<HTML>
    <HEAD>
        <title>
            Ejercicio 2. Rescatar una opción de la pregunta 99999.
        </title>
    </HEAD>
    <body>
        {if isset($lastOpResult) && count($lastOpResult)>0}   
            <H2>Resultado de la última operación:</H2>
        <UL>
            {foreach $lastOpResult as $result}
            <LI>{$result}</LI>       
            {/foreach}
        </UL>
        {/if}
        
        {if $op=='pedirdatos' or $op=='borrar'}            
            <H2>Borrar opción de la pregunta 99999</H2>
            <form action="" method="post">   
                 <LABEL>Id de la pregunta a borrar:
                    <input type="text" name="idopcion" value="">                    
                </LABEL>
                <button type="submit" name="btn_delete">¡Borrar!</button>
            </form>
        {elseif $op=='pedirconfirmacion' }
            <H2>Confirmación de borrado de la opción {$idopcion_a_borrar} </H2>
            <form action="" method="post">                    
                ¿En realidad quieres borrarla?
                <button type="submit" name="btn_confirm_delete">¡Si, quiero borrarla!</button>
                <button type="submit" name="btn_cancel_delete">¡No, no quiero borrarla!</button>
                <input type='hidden' name='randomcheck' value='{$randomcheck}'>
                 | <a href='ejercicio2nueva.php'> Ir a Ejercicio 2 Nueva para editar </a> | 
                <a href='ejercicio2nueva.php'> Ir a Ejercicio 2 Rescate para ver </a>
            </form>        


        {/if}                        
            
        </form>

    </body>
    
</HTML>
