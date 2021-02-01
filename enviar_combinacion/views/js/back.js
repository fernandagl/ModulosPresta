/**
* 2007-2020 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    Alex Gones <alex.gon@barcelonaled.com>, Tazim Nadimul <tazim@barcelonaled.com>, Fernanda Gonçalves <fernanda@barcelonaled.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
function pop(div) {
	document.getElementById(div).style.display='block';
	return false;
}
function hide(div) {
	document.getElementById(div).style.display='none';
	return false;
}
$(document).ready(function() {
	
			$("#contenidofancy").fancybox({type : "ajax"}); 
	
          
});
function ejecutarAjax(){
    var idcombinaciones=[];
        $("input[name='checkcombi']").each(function(index){

        if(this.checked)idcombinaciones.push(this.dataset.idProductAttribute);
    });

    var idshops=[];
        $("input[name='checkshop']").each(function(index){

        if(this.checked)idshops.push(this.dataset.idShop);
    });

$.ajax({
    url: urlControlador,
    data: {
        action: "movercombinaciones",
        idShop:idshops,
        idcombinaciones:idcombinaciones,
        id_producto:id_producto
    },
    type: "POST",
    method: "POST",
    success: function(response) {
        $(response).fancybox().click();
    
    } 
});
}