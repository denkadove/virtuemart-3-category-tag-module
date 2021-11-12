<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$meta = '';
$document->setMetadata('keywords', $meta );
$view = JRequest::getVar('view', null); 
if (($view !== "productdetails") && (count($unique_category_list)>'1'))  {
?>
<div class="tag_button">
    <a href="#" onclick="open_tag_container(); return false;" id="tag_container_link">Больше...</a>
</div>
<div class="tag_container" id="tag_container">
    <?php foreach ($unique_category_list as $i => $value) {
        $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$value['id']);
        $cattext = $value['name'];
        $alt = array('alt' => $value['alt']); 
  
    if(!in_array($category->virtuemart_category_id, $id)){ ?>            
        <div class="vmcat_tag-block">
            <?php if($img){ ?>
                <div class="vmcat_tag-image"></div>
            <?php } ?>
            <div class="vmcat_tag-name">
                <?php echo JHTML::link($caturl, $cattext, $alt); ?>
                <?php if($count) {
                    $countProduct = $categoryModel->countProducts($category->virtuemart_category_id);
                    echo '<span>('.$countProduct.')</span>';
                } ?>                           
            </div>
        </div>
    <?php } ?>
    <?php } ?>  
</div>
           
<!-- TODO: replace script to custom.js -->
<script>
function open_tag_container(){
    container = document.getElementById('tag_container');
    if (container.style.height == "100%"){
        document.getElementById('tag_container').style.height=38+'px';
        document.getElementById('tag_container_link').firstChild.nodeValue = "Больше...";
    } else {
        document.getElementById('tag_container').style.height=100+'%';
        document.getElementById('tag_container_link').lastChild.nodeValue = "Меньше...";
    }    
}
</script>
<?php } ?> 
                                             