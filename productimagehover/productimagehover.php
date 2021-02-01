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
*  @author Fernanda Gonçalves <fernanda@barcelonaled.com>
*  @copyright 2007-2020 PrestaShop SA
 * @email: fernanda@barcelonaled.com
 * First created: 18/01/2021
 * Last updated: NOT YET
*/
if (!defined('_PS_VERSION_'))
	exit;
/**
 * Includes 
 */   
class Productimagehover extends Module
{    
    private $_hooks = array('productImageHover','displayHeader');
    public function __construct()
	{
		$this->name = 'productimagehover';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Fernanda Gonçalves Leal fernanda@barcelonaled.com';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Imagen producto [miniatura hover]');
		$this->description = $this->l('Muestra imágenes miniatura cuando el usuario pasa por encima de una combinación que tiene una imagen asociada');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);   
        
        $this->confirmUninstall = $this->l('¿Seguro que deseas desinstalar el módulo?');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }

    }
     /**
	 * @see Module::install()
	 */
    public function install()
	{
	    $res = parent::install();        
        foreach($this->_hooks as $hook)
        {
            $res &= $this->registerHook($hook);
        }  
        //Configuration::updateValue('PI_TRANSITION_EFFECT','faded');
        return  $res;
    }
    /**
	 * @see Module::uninstall()
	 */
	public function uninstall()
	{        
        return parent::uninstall();
    }
    public function hookDisplayHeader()
    {       
		$this->context->controller->addJS($this->_path.'views/js/front/productimagehover.js', 'all');  
    }
    public function hookProductImageHover($params)
    { 
       if(isset($params['id_product'])&& isset($params['variant']))
        {
            $product_id=$params['id_product'];
            $variant = $params['variant'];
            $id_imageDefault = "0";
			
            $sql= "SELECT id_image 
                   FROM  `"._DB_PREFIX_."image` 
                   WHERE  `id_product` =  $product_id AND (cover = 0 OR cover is null) ORDER BY  `position` ASC";
				   
            $image = Db::getInstance()->getRow($sql);
  
            if(!$image)
            {

                $sql= "SELECT id_image 
                       FROM  `"._DB_PREFIX_."image` 
                       WHERE  `id_product` =  $product_id AND cover =  1 ORDER BY  `position` ASC";
                $image = Db::getInstance()->getRow($sql); 

                $product = new Product($product_id,false,$this->context->language->id,$this->context->shop->id);   
                return $this->context->link->getImageLink($product->link_rewrite,(int)$image['id_image'], 'home_default');
            }
            if($image){
                $product = new Product($product_id,false,$this->context->language->id,$this->context->shop->id);
                $id_image = Image::getBestImageAttribute($this->context->shop->id, $this->context->language->id, $product_id, $variant['id_product_attribute']);

                if(!$id_image){
                    $sql= "SELECT id_image 
                    FROM  `"._DB_PREFIX_."image` 
                    WHERE  `id_product` =  $product_id AND cover =  1 ORDER BY  `position` ASC";

                    $id_imageDefault = Db::getInstance()->getRow($sql); 
                    return $this->context->link->getImageLink($product->link_rewrite,(int)$id_imageDefault['id_image'], 'home_default');
                }else{
				
                return $this->context->link->getImageLink($product->link_rewrite,(int)$id_image['id_image'], 'home_default');		
                }	  
                
            }
        }
    }
}