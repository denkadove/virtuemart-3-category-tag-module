<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->setMetadata('keywords', '' );
$view = JRequest::getVar('view', null);

if (($view !== "productdetails") && (count($unique_category_list)>'1')){
?>
<div class="category_as_tag">
    <input type="checkbox" class="caterory_tag_read_more_checker" id="caterory_tag_read_more_checker" />
    <div class="caterory_tag_limiter">
        <div class="tag_container" id="tag_container">
            <?php
                $unique_category_list = get_icon_list($actual_category_number);
                foreach ($unique_category_list as $i => $value) {
                    $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$value['id']);
                    $cattext = $value['name'];
                    $alt = array('alt' => $value['alt']);
             ?>
                    <div class="vmcat_tag-block">
                        <div class="vmcat_tag-name">
                            <?php echo JHTML::link($caturl, $cattext, $alt); ?>
                        </div>
                    </div>
            <?php } ?>

        </div>
    <!-- Здесь сам контент который должен будет скрываться / раскрываться -->
        <div class="caterory_tag_bottom"></div>
    </div>
    <label for="caterory_tag_read_more_checker" class="caterory_tag_read_more_button"></label>
</div>
         
<?php } ?>                                           