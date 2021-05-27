<!DOCTYPE html>
<HTML>
    <HEAD>
        <title>
            Ejercicio 5. Borrar una pregunta o opción en tipo EligeUna.
        </title>
    </HEAD>
    <body>
        <H1>Ejercicio 5. Borrar una pregunta o opción en tipo EligeUna.</H1>
        <H3>Autor/a: profesor</H3>    
        
        {if count($lastOpResult)>0}
            <H2>Resultado de la última operación:</H2>
        <UL>
            {foreach $lastOpResult as $result}
            <LI>{$result}</LI>       
            {/foreach}
        </UL>
        {/if}              
        
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
                | <a href="ejercicio5nueva.php">Ir a Ejercicio 5 Nueva</a>
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
