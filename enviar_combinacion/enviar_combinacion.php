<?php
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
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Enviar_combinacion extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'enviar_combinacion';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Alex Gonesp <alex.gon@barcelonaled.com>,Tazim Nadimul <tazim@barcelonaled.com>,Fernanda Gonçalves <fernanda@barcelonaled.com>';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Exportar combinaciones de producto');
        $this->description = $this->l('Este módulo permite exportar combinaciones de producto a otras tiendas');

        $this->confirmUninstall = $this->l('Estás seguro que quieres desinstalar este modulo?');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided.');
        }
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {

        return parent::install() &&
            $this->registerHook('actionAdminControllerSetMedia') &&
            $this->registerHook('displayAdminProductsQuantitiesStepBottom') ;
            
    }

    public function uninstall()
    {

        return parent::uninstall();
    }     
    



    public function hookDisplayAdminProductsQuantitiesStepBottom($params) {        
       
        $this->context->smarty->assign("id_producto",$params['id_product']);
        $this->context->smarty->assign ("urlControlador",$this->context->link->getAdminLink("AdminMoverCombinacionesAOtrasTiendas", true, [], ["ajax" => true]));
        return $this->display(__FILE__, 'boton_exportar.tpl');
        
    }

    public function hookActionAdminControllerSetMedia()
    {
        
        if ($this->context->controller->php_self == 'AdminProducts'){
            $this->context->controller->addJquery();
            $this->context->controller->addJqueryPlugin('fancybox');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            Media::addJsDef(['urlControlador' => $this->context->link->getAdminLink("AdminMoverCombinacionesAOtrasTiendas", true, [], ["ajax" => true])]);
        }
    }
}
