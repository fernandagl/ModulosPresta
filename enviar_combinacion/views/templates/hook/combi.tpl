  
<div>
{if $error}
<p>{$error}</p>
{/if}
	<h2>Exportar combinaciones</h2>
    <h3>Referencias a exportar</h3>
    
    <table>
  {foreach $combinaciones as $combinacion}
    <tr>
    <td>{$combinacion.Referencia}→ </td>
    
    <td><input type="checkbox" data-id-product-attribute="{$combinacion.Id}"  name ="checkcombi"></td>
    </tr>
    {/foreach}
    </table>
    
    <br>
    <h3> Tiendas a exportar</h3>
    <table>
  {foreach $shops as $shop}
    <tr>
    <td>{$shop.name}→ </td>
    <td><input type="checkbox" data-id-shop="{$shop.id_shop}"  name ="checkshop"></td>
    </tr>
    {/foreach}
    </table>
        <br>

    <div>
      <button class="btn btn-primary" onclick = "ejecutarAjax()">Exportar</button>
    </div>
    </div>
   

  