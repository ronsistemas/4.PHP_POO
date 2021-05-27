<!DOCTYPE html>
<HTML>
    <HEAD>
        <title>
            Ejercicio 6. Añadir una pregunta tipo EligeVarias.
        </title>
    </HEAD>
    <body>
        <H1>Ejercicio 6. Añadir una pregunta tipo EligeVarias.</H1>
        <H3>Autor/a: profesor</H3>    
        
        {include file='logacciones.tpl'}                  
        
        {if isset($pregunta) && $pregunta->getId()!=null}
            <div style="border:1px solid black;background:lightgray;padding:10px">{$pregunta->getEnunciado()}</div>
            {foreach $pregunta->getOpciones() as $opcion}
                <div  style="border:1px dotted blue; margin:5px 0px 5px 40px; padding:10px;{if $opcion->getCorrecta()}background:lightgreen{else}background:orange{/if}">
                    {$opcion->getTextoOpcion()}
                    
                </div>
            {/foreach}
            <form action="" method="post">                
                <button type="submit" name="btn_liberate">¡Nueva pregunta!</BUTTON>  | <a href="ejercicio6borrar.php">Ir a Ejercicio 6 borrar</a>
            </form>
        {else}
            
            <form action="" method="post">
                <label> Enunciado:<br>
                <textarea name="enunciado" rows="4" cols="60"></textarea><br>
                </label>
                {for $var=1 to 6 step 1}                    
                <LABEL>Opcion {$var}:
                <input type="text" name="texto_opcion[{$var}]" value="">
                </LABEL>
                <LABEL>¿Es correcta?
                    <input type="checkbox" name="correcta[]" value="c{$var - 1}">
                </LABEL><br>
                {/for}                
                <button type="submit" name="btn">¡Guardar!</BUTTON>                
            </form>
        {/if}    
        
        
    </body>
    
</HTML>
