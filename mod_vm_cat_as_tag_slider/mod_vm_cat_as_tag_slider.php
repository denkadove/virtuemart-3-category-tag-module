<?php
defined('_JEXEC') or  die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

if (!class_exists( 'VmConfig' )) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');

VmConfig::loadConfig();
VmConfig::loadJLang('mod_vm_cat_as_tag_slider', true);

function vardump($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

function unique_multidim_array($array, $key) { 
    $temp_array = array(); 
    $i = 0; 
    $key_array = array(); 
    
    foreach($array as $val) { 
        if (!in_array($val[$key], $key_array)) { 
            $key_array[$i] = $val[$key]; 
            $temp_array[$i] = $val; 
        } 
        $i++; 
    } 
    return $temp_array; 
}

$actual_category_number = JRequest::getVar('virtuemart_category_id', null);

function get_icon_list($actual_category_number){
    $cat = VmModel::getModel('category');
    $productModel = VmModel::getModel('Product');

    $prod_list = $productModel->getProductsListing($group = FALSE, $nbrReturnProducts = 500,
                                                $withCalc = TRUE, $onlyPublished = TRUE,
                                                $single = FALSE, $filterCategory = TRUE,
                                                $category_id = $actual_category_number, $filterManufacturer = TRUE,
                                                $manufacturer_id = 0, $omit = 0);
    $category_list = array();

        //получаем список id продуктов на странице
        foreach ($prod_list as $i => $value) {
            $product_id_list[$i] = $value->virtuemart_product_id;
        }

        //для каждого продукта получаем список категорий
        foreach ($product_id_list as $i => $value) {
            $cat_list[$i] = $productModel->getProductCategories($product_id_list[$i]);

            //для каждой категории получаем id, имя и metakey (используется в качестве названия бейджа)//
            foreach ($cat_list[$i] as $j => $value) {
                $id = $cat_list[$i][$j]['virtuemart_category_id'];
                $name = $cat_list[$i][$j]['category_name'];
                $alt = $cat_list[$i][$j]['metakey'];
                $published = $cat_list[$i][$j]['published'];

                if (!$alt) {
                    $array = array('id' => $id, 'name' => $name, 'alt' => $name);
                } else {
                    $array = array('id' => $id, 'name' => $alt, 'alt' => $name);
                }

                if ($published) {
                    array_push($category_list, $array);
                }
            }
        }
        return unique_multidim_array($category_list,'id');
}

$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$cache = JFactory::getCache('cat_tag_mod', '');
$cache->setCaching(true); // Force cache enable
$cache->setLifeTime(1500); // Minutes
$cacheKey = serialize($url);
 
if (!($unique_category_list = $cache->get($cacheKey, '')))
{
    $unique_category_list = get_icon_list($actual_category_number);
    $cache->store($unique_category_list, $cacheKey);    
}

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'modules/mod_vm_cat_as_tag_slider/assets/vm_cat_as_tag_slider.css');
$doc->addStyleDeclaration($style);
require(JModuleHelper::getLayoutPath('mod_vm_cat_as_tag_slider',$layout));