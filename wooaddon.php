<?php
/**
Plugin Name: Custom Field Tab for WooCommerce Product
Plugin URI: http://www.romalpatel.ml/plugins/wooaddon.zip
Description: This plugin is used for add custom field for woocommerce products to add customized attributes of products. Plugin also display custom tab data on product page.
Author: Romal Patel
Version: 1.0
Author URI: romalpatel.ml
*/

add_filter( 'woocommerce_product_data_tabs', 'rp_woo_product_add_on', 10, 1 );

function rp_woo_product_add_on($tabs) {

	$tabs['custom_tab'] = array(
		'label'   =>  __( 'Add On', 'woocommerce' ),
		'target'  =>  'woo_new_product_tab_content',
		'priority' => 60,
		'class'   => array()
	);

	return $tabs;
}

add_action( 'woocommerce_product_data_panels', 'rp_woo_new_product_tab_content' );

function rp_woo_new_product_tab_content() {

	$prod_id = get_the_ID();

	$post_data = (get_post_meta($prod_id,'_wk_rp_addon_data',true)!='')?get_post_meta($prod_id,'_wk_rp_addon_data',true):'';


/*	echo '<pre>';
	$addonarr = json_decode($post_data);
	print_r($addonarr);
	echo '</pre>';*/

	$form = <<< ADDON
 <div id="woo_new_product_tab_content" class="panel woocommerce_options_panel">
	<div class="options_group">
		<table border="0" width="96%" align="center" id="tbl_addon" data-attr='$post_data' >
			<thead>
			<tr style="background-color: cornsilk;">
				<th>Lable</th>
				<th>Value</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><input type="text" name="addons[0][label]" class="input-text" placeholder="label" style="width: 100%;"></td>
				<td><input type="text" name="addons[0][value]" class="input-text" placeholder="value" style="width: 100%;"></td>
				<td></td>
			</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" align="right"><input type="button" id="addmore" value="Add More" onclick="javascript:addmorerow(this);" data-action="add"/></td>
				</tr>
			</tfoot>
		</table>	
	</div>
 </div>
ADDON;

echo $form;
}


function rp_admin_load_js($hook){

	global $post;

	if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
		if ( 'product' === $post->post_type ) {
			wp_enqueue_script( 'custom_js', plugins_url( '/js/addon.js', __FILE__ ), array('jquery') );

		}
	}
}
add_action('admin_enqueue_scripts', 'rp_admin_load_js');

function rp_woo_addon_save($post_id) {
	$postArr = $_POST['addons'];

	foreach($postArr as $k =>$addon) {
		if (!empty($addon['label']) && !empty($addon['value'])) {
			$finalArr[] = [sanitize_text_field($addon['label'])=>sanitize_text_field($addon['value'])];
		}
	}

	update_post_meta($post_id, '_wk_rp_addon_data',json_encode($finalArr));
}

add_action('woocommerce_process_product_meta_simple', 'rp_woo_addon_save');

add_filter( 'woocommerce_product_tabs', 'rp_woo_new_product_tab' );
function rp_woo_new_product_tab( $tabs ) {
// Adds the new tab
	$tabs['desc_tab'] = array(
		'title'     => __( 'Addon', 'woocommerce' ),
		'priority'  => 50,
		'callback'  => 'rp_display_tab_content_on_front'
	);
	return $tabs;
}

function rp_display_tab_content_on_front()  {
	// The new tab content
	$prod_id = get_the_ID();

	$addon = get_post_meta($prod_id,'_wk_rp_addon_data',true);

	if($addon) {
		$addonArr = json_decode($addon);

		foreach($addonArr as $k => $obj) {
			foreach($obj as $label => $values) {
				echo '<p>',$label,' : ',$label,'</p>';
			}
		}
	}
}