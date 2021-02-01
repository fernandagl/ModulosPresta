<?php
class AdminMoverCombinacionesAOtrasTiendasController extends ModuleAdminController {

	public function __construct(){
		$this->ajax = '1';
		parent::__construct();
	}

	public function ajaxProcess() {
        $result = ['success' => true, 'errors' => []];
        $action = Tools::getValue('action');
        $this->{$action}($result);			

    die(json_encode($result));
	}
    public function movercombinaciones(&$result){
                 
        $idtiendas = Tools::getValue('idShop');
        $idcombinaciones = Tools::getValue('idcombinaciones');

         if (!is_array($idtiendas)){

            die( $this->context->smarty->fetch(_PS_MODULE_DIR_."enviar_combinacion/views/templates/hook/error_no_shop_select.tpl"));
         }

         if (!is_array($idcombinaciones)){

            die( $this->context->smarty->fetch(_PS_MODULE_DIR_."enviar_combinacion/views/templates/hook/no_check_combi.tpl"));
         }

        foreach($idcombinaciones as $idcomb){
            foreach($idtiendas as $id_tienda){
                $sql = sprintf("INSERT IGNORE INTO `"._DB_PREFIX_."product_attribute_shop` SELECT ".$this->obtenerConsultaSql()." FROM `"._DB_PREFIX_."product_attribute_shop`  WHERE `id_product_attribute`=".$idcomb,$id_tienda);
                $result['success'] = $result['success'] && Db::getInstance()->execute($sql);	
                $result['errors']= Db::getInstance()->getMsgError();
            }
        }

        if (Db::getInstance()->execute($sql)){
            die( $this->context->smarty->fetch(_PS_MODULE_DIR_."enviar_combinacion/views/templates/hook/ha_ido_bien.tpl"));
        }
        
        else{
            $this->context->smarty->assign("errors",$result['errors']);  
            die( $this->context->smarty->fetch(_PS_MODULE_DIR_."enviar_combinacion/views/templates/hook/errors_msg_sql.tpl"));
        }
    }
    public function mostrarcombis($result, $error = false){
       
        if (Shop::getContext()==Shop::CONTEXT_SHOP) {
            $id_producto = Tools::getValue('id_producto');

            $combination_ids = Product::getProductAttributesIds($id_producto, true);
            $combinations= [];
            foreach ($combination_ids as $combination_id){
                $combination= new Combination($combination_id['id_product_attribute']);
                $combinations[]=["Referencia"=> $combination->reference, "Id"=>$combination->id];
                }
            $this->context->smarty->assign("combinaciones",$combinations);
            $this->context->smarty->assign("error",$error);

            //Obtiene array con la tabla id_shops, donde están los id's y nombres de todas las tiendas de la multitienda
            $id_shops = Shop::getShops(true);
            //Pasamos el array a smarty para acceder a él desde el tpl
            $this->context->smarty->assign("shops",$id_shops);

            if ($combinations == null){
                die( $this->context->smarty->fetch(_PS_MODULE_DIR_."enviar_combinacion/views/templates/hook/error_no_combi.tpl"));
            }
            else{

            die( $this->context->smarty->fetch(_PS_MODULE_DIR_."enviar_combinacion/views/templates/hook/combi.tpl"));
            }
        }
        else if (Shop::getContext()==Shop::CONTEXT_ALL){
    
            die( $this->context->smarty->fetch(_PS_MODULE_DIR_."enviar_combinacion/views/templates/hook/error_contexto_tienda.tpl"));
         }
    }
    public function obtenerConsultaSql(){
        
        $parametro= include(_PS_ROOT_DIR_."/app/config/parameters.php");
        $nombre_db = $parametro['parameters']['database_name'];
        $consulta_array_columnas_db = ("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$nombre_db."' AND TABLE_NAME = '"._DB_PREFIX_."product_attribute_shop';");
        $array_columnas_db=Db::getInstance()->executeS($consulta_array_columnas_db);
        $array_definitivo=[];
        foreach ($array_columnas_db as $key => $value) {
            if ($value['COLUMN_NAME'] == 'id_shop'){
                $array_definitivo[]= "%d";
            }
            else{
            $array_definitivo[]= "`".$value['COLUMN_NAME']."`";

            }
          };
          return  implode(",", $array_definitivo);
    }
}
    
    