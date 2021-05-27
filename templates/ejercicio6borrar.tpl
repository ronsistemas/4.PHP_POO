<!DOCTYPE html>
<HTML>
    <HEAD>
        <title>
            Ejercicio 6. Borrar una pregunta o opción en tipo EligeVarias.
        </title>
    </HEAD>
    <body>
        <H1>Ejercicio 6. Borrar una pregunta o opción en tipo EligeVarias.</H1>
        <H3>Autor/a: profesor</H3>    
        
        {include file='logacciones.tpl'}                  
          
        
        {if isset($pregunta) && $pregunta->getId()!=null}
            <form action="" method="post">                

            <div style="border:1px solid black;background:lightgray;padding:10px">{$pregunta->getEnunciado()}</div>
            {foreach $pregunta->getOpciones() as $opcion}
                <div  style="border:1px dotted blue; margin:5px 0px 5px 40px; padding:10px;{if $opcion->getCorrecta()}background:lightgreen{else}background:orange{/if}">
                    {$opcion->getTextoOpcion()}
                    
                </div>
            {/foreach}
                <button type="submit" name="btn_liberate">Examinar otra pregunta.</BUTTON>
            {if count($pregunta->getOpciones())>2}
                <button type="submit" name="btn_delete_opcion">Borrar opción al azar.</BUTTON>
            {else}
            <button type="submit" name="btn_delete_pregunta">Borrar pregunta.</BUTTON>
            {/if}
                | <a href="ejercicio6nueva.php">Ir a Ejercicio 6 Nueva</a>
            </form>
        {else}
            
            <form action="" method="post">                
                <LABEL>Indica el número de pregunta a borrar:
                <input type="text" name="idpregunta" value="">
                </LABEL>
                <button type="submit" name="btn">¡Rescatar!</BUTTON>
            </form>
        {/if}    
        
        
    </body>
    
</HTML>
