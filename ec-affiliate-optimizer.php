<?php
/*
 * Plugin Name: EchoCurrent Product Affiliate Optimizer
 * Plugin URI: http://www.echocurrent.com
 * Description: The Affiliate Product Optimizer automatically displays targeted product offers pulled from the Commission Junction affiliate product catalog.  You can include an APO Zone in one of 3 different ways: 1) as a sidebar widget through the regular Widget Manager, 2) as a template tag by adding <code>&lt;?php echocurrent_apo_zone($zone_id, $echocurrent_id, $zone_style, $options); ?&gt;</code> to your template, or 3) as shortcode in a post with <code>[echocurrent_apo_zone zone_id="0123456789" echocurrent_id="your-id-here" zone_style="fullBanner"]</code>.  For more detail about adding a zone to your WordPress blog, <a target="_blank" href="http://www.echocurrent.com/Affiliate-Product-Optimizer/adding-a-zone-to-wordpress.html">please see our documentation</a>. 
 * Version: 1.1
 * Author: EchoCurrent Software, Inc.
 * Author URI: http://www.echocurrent.com
 */
/*
 * TODO:  FILL IN THIS COPYWRITE SECTION
 */
 
  // Admin form callback function.  Will provide a simple form allowing the blog master to manage the options (such as EchoCurrent customer key and zone style
  function echocurrent_affiliate_control() {
		
	$possible_zone_styles = array(
			'rectangleText' => 'Rectangle Text (180 x 150)',
			'mediumRectangleText' => 'Medium Rectangle Text (300 x 250)',
			'verticalRectangleText' => 'Vertical Rectangle Text (240 x 400)',
			'wideBanner' => 'Wide Banner (702 x 60)',
			'fullBanner' => 'Full Banner (468 x 60)',
			'halfBanner' => 'Half Banner (234 x 60)',
			'verticalBanner' => 'Vertical Banner (120 x 240)',
			'narrowSkyscraper' => 'Skyscraper (120 x 600)',
			'skyscraper' => 'Wide Skyscraper (160 x 600)',
			'leaderboard' => 'Leaderboard (728 x 92)');
  	
  	$options = get_option('echocurrent_affiliate_optimizer');
  	// Init defaults, if necessary
  	if(!is_array($options)) {
  		$options = array(
			'website_key' => '',
			'zone_style' => '',
			'external_border_color' => '#000000',
			'internal_border_color' => '#6699FF',
			'prod_name_font_color' => null,
			'prod_desc_font_color' => '#000000',
			'alt_font_color' => '#008800');
  	}
  	
  	// Form posted?
  	if($_POST['echocurrent_affiliate_submit']) {
  		$ws_key = strip_tags(stripslashes($_POST['ec_website_key']));
		$start_pos = strpos($ws_key, '#');
		if((string)$start_pos === (string)0) {
			$ws_key = substr($ws_key, strlen('#'));
		}
  		$options['website_key'] = $ws_key;
  		$options['zone_style'] = strip_tags(stripslashes($_POST['ec_zone_style']));
  		$options['external_border_color'] = strip_tags(stripslashes($_POST['ec_external_border_color']));
  		$options['internal_border_color'] = strip_tags(stripslashes($_POST['ec_internal_border_color']));
  		$options['background_color'] = strip_tags(stripslashes($_POST['ec_background_color']));
  		$options['prod_name_font_color'] = strip_tags(stripslashes($_POST['ec_prod_name_font_color']));
  		$options['prod_desc_font_color'] = strip_tags(stripslashes($_POST['ec_prod_desc_font_color']));
  		$options['alt_font_color'] = strip_tags(stripslashes($_POST['ec_alt_font_color']));
  		update_option('echocurrent_affiliate_optimizer', $options);
  	}
  	
  	$website_key_option = htmlspecialchars($options['website_key'], ENT_QUOTES);
  	$zone_style_option = htmlspecialchars($options['zone_style'], ENT_QUOTES);
  	$external_border_color_option = htmlspecialchars($options['external_border_color'], ENT_QUOTES);
  	$internal_border_color_option = htmlspecialchars($options['internal_border_color'], ENT_QUOTES);
  	$background_color_option = htmlspecialchars($options['background_color'], ENT_QUOTES);
  	$prod_name_font_color_option = htmlspecialchars($options['prod_name_font_color'], ENT_QUOTES);
  	$prod_desc_font_color_option = htmlspecialchars($options['prod_desc_font_color'], ENT_QUOTES);
  	$alt_font_color_option = htmlspecialchars($options['alt_font_color'], ENT_QUOTES);
  	
  	echo '<script src="../wp-includes/js/colorpicker.js" type="text/javascript" language="JavaScript"></script>
		  <script type="text/javascript">
		    var cp = new ColorPicker("window");
		  </script>';
  	
	// The form fields
	echo '<p style="text-align:left;">
			<label for="ec_website_key">EchoCurrent Key
			<input style="width: 200px;" id="ec_website_key" name="ec_website_key" type="text" value="'.$website_key_option.'" />
			</label></p>';
	echo '<p style="text-align:left;">
			<label for="ec_zone_style">Zone Style
			<select id="ec_zone_style" name="ec_zone_style" value="' . $zone_style_option . '">';
	foreach($possible_zone_styles as $current_style => $label) {
		echo _generate_zone_style_option($zone_style_option, $current_style, $label);
	}
	echo '  </select>
			</label></p>';
	echo '<p style="text-align:left;">
			<label for="ec_external_border_color">External Border Color
			<input style="width: 200px;" id="ec_external_border_color" name="ec_external_border_color" type="text" value="'.$external_border_color_option.'" />
			<A HREF="#" onClick="cp.select(document.getElementById(\'ec_external_border_color\'),\'external_border_color_picker\');return false;" NAME="external_border_color_picker" ID="external_border_color_picker">Pick</A>
			</label></p>';
	echo '<p style="text-align:left;">
			<label for="ec_internal_border_color">Internal Border Color
			<input style="width: 200px;" id="ec_internal_border_color" name="ec_internal_border_color" type="text" value="'.$internal_border_color_option.'" />
			<A HREF="#" onClick="cp.select(document.getElementById(\'ec_internal_border_color\'),\'internal_border_color_picker\');return false;" NAME="internal_border_color_picker" ID="internal_border_color_picker">Pick</A>
			</label></p>';
	echo '<p style="text-align:left;">
			<label for="ec_background_color">Background Color
			<input style="width: 200px;" id="ec_background_color" name="ec_background_color" type="text" value="'.$background_color_option.'" />
			<A HREF="#" onClick="cp.select(document.getElementById(\'ec_background_color\'),\'zone_background_color_picker\');return false;" NAME="zone_background_color_picker" ID="zone_background_color_picker">Pick</A>
			</label></p>';
	echo '<p style="text-align:left;">
			<label for="ec_prod_name_font_color">Product Name Font Color
			<input style="width: 200px;" id="ec_prod_name_font_color" name="ec_prod_name_font_color" type="text" value="'.$prod_name_font_color_option.'" />
			<A HREF="#" onClick="cp.select(document.getElementById(\'ec_prod_name_font_color\'),\'prod_name_font_color_picker\');return false;" NAME="prod_name_font_color_picker" ID="prod_name_font_color_picker">Pick</A>
			</label></p>';
	echo '<p style="text-align:left;">
			<label for="ec_prod_desc_font_color">Product Description Font Color
			<input style="width: 200px;" id="ec_prod_desc_font_color" name="ec_prod_desc_font_color" type="text" value="'.$prod_desc_font_color_option.'" />
			<A HREF="#" onClick="cp.select(document.getElementById(\'ec_prod_desc_font_color\'),\'prod_desc_font_color_picker\');return false;" NAME="prod_desc_font_color_picker" ID="prod_desc_font_color_picker">Pick</A>
			</label></p>';
	echo '<p style="text-align:left;">
			<label for="ec_alt_font_color">Alternate Font Color
			<input style="width: 200px;" id="ec_alt_font_color" name="ec_alt_font_color" type="text" value="'.$alt_font_color_option.'" />
			<A HREF="#" onClick="cp.select(document.getElementById(\'ec_alt_font_color\'),\'alt_font_color_picker\');return false;" NAME="alt_font_color_picker" ID="alt_font_color_picker">Pick</A>
			</label></p>';
  	
  	echo '<p style="text-align:right;">
  		    Please visit <a href="http://www.echocurrent.com">EchoCurrent</a> for ' . ( $website_key_option == null || $website_key_option == '' ? 'registration information' : 'reports') . '.
  		  </p>';
  		  
	echo '<input type="hidden" id="echocurrent_affiliate_submit" name="echocurrent_affiliate_submit" value="1" />';
  	
  }
  
  function _generate_zone_style_option($zone_style_option, $current_zone_style, $label) {
  	return '<option value="' . $current_zone_style . '"' . ($zone_style_option == $current_zone_style ? ' SELECTED' : '') . '>' . $label . '</option>';
  }
 
  // Widget callback function.  Will generate the EchoCurrent Affiliate Products zone, based on options configured by the blog admin
  function echocurrent_affiliate_zone($args) {
    extract($args);
    echo $before_widget;
    echo $before_title . /* No Title */ '' . $after_title;
    $options = get_option('echocurrent_affiliate_optimizer');
    $website_key = $options['website_key'];
    if(empty($website_key)) $website_key = '0123456789';
    $zone_style = $options['zone_style'];
    echocurrent_apo_zone('sidebar_widget_ec_apo_zone', $website_key, $zone_style, $options);
    echo $after_widget;
  }
  
  /*
   * This is the self-closing shortcode for the EchoCurrent Affiliate Product Optimizer.
   * Putting this in your post will generate and render a zone,
   * with the specified attributes.
   * 
   * Attributes:
   *   zone_id (required) - A unique ID for this zone
   *   echocurrent_id (required) - The ID that you recieved when registering for EchoCurrent APO
   *   zone_style (required) - [rectangleText | mediumRectangleText | verticalRectangleText | wideBanner | fullBanner | halfBanner | verticalBanner | narrowSkyscraper | skyscraper | leaderboard]
   *   external_border_color
   *   internal_border_color
   *   background_color
   *   product_name_font_color
   *   product_desc_font_color
   *   alt_font_color
   * 
   * Usage:
   *   [echocurrent_apo_zone zone_id="testZone" echocurrent_id="0123456789" zone_style="fullBanner"]
   *   [echocurrent_apo_zone zone_id="differentColorZone" echocurrent_id="0123456789" zone_style="leaderboard" external_border_color="yellow" internal_border_color="red"]
   */
  function echocurrent_zone_shortcode($atts) {
  	extract(shortcode_atts(array(
  		'zone_id' => '',
		'echocurrent_id' => '0123456789',
		'zone_style' => '',
		'external_border_color' => '#000000',
		'internal_border_color' => '#6699FF',
		'prod_desc_font_color' => '#000000',
		'alt_font_color' => '#008800'
	), $atts));
	if(empty($zone_id) || empty($zone_style)) {
		return "<i>EchoCurrent Shortcode ($echocurrent_id) - Missing zone_id ($zone_id) or zone_style ($zone_style) attribute(s)</i>";
	} else {
		return echocurrent_generate_zone($zone_id, $echocurrent_id, $zone_style, $atts);
  	}
  }
  
  /*
   * This is the Template Tag for the EchoCurrent Affiliate Product Optimizer.
   * Putting this template tag in your template will generate and render a zone,
   * with the specified arguments.
   * 
   * Parameters:
   *   zone_id (required) - A unique ID for this zone
   *   echocurrent_id (required) - The ID that you recieved when registering for EchoCurrent APO
   *   zone_style (required) - [rectangleText | mediumRectangleText | verticalRectangleText | wideBanner | fullBanner | halfBanner | verticalBanner | narrowSkyscraper | skyscraper | leaderboard]
   *   options - A hash of styling options for the zone.  Options include: external_border_color, internal_border_color, background_color, product_name_font_color, product_desc_font_color, alt_font_color
   * 
   * Usage:
   *   <?php echocurrent_apo_zone('testZone', '0123456789', 'fullBanner'); ?>
   *   <?php echocurrent_apo_zone('differentColorZone', '0123456789', 'leaderboard', array('external_border_color' => 'yellow', 'internal_border_color' => 'red')); ?>
   */
  function echocurrent_apo_zone($zone_id, $echocurrent_id, $zone_style, $options = array()) {
  	echo echocurrent_generate_zone($zone_id, $echocurrent_id, $zone_style, $options);
  }
  
  function echocurrent_generate_zone($zone_id, $echocurrent_id, $zone_style, $options = array()) {
    $ec_server_loc = 'services.echocurrent.com'; // PROD
//    $ec_server_loc = 'ec-dbrunette:8090/echocurrent-webapp'; // DEV

	// ensure defaults
	if(empty($options['external_border_color'])) $options['external_border_color'] = '#000000';
	if(empty($options['internal_border_color'])) $options['internal_border_color'] = '#6699FF';
	if(empty($options['prod_desc_font_color'])) $options['prod_desc_font_color'] = '#000000';
	if(empty($options['alt_font_color'])) $options['alt_font_color'] = '#008800';
	
	$snippet = "";
	$snippet .= "<div class='echocurrent_affiliatezone'>\n";
	$snippet .= "	<script type='text/javascript'><!--//<![CDATA[\n";
	$snippet .= "	  var ec_recordForWordPress = true;\n";
	$snippet .= "	  var ec_websiteKey = '$echocurrent_id';\n";
	$snippet .= "	  var ec_zoneStyle = '$zone_style';\n";
	$snippet .= "	  var ec_productNameFontColor = '" . $options["prod_name_font_color"] . "';\n";
	$snippet .= "	  var ec_productDescFontColor = '" . $options["prod_desc_font_color"] . "';\n";
	$snippet .= "	  var ec_altFontColor = '" . $options["alt_font_color"] . "';\n";
	$snippet .= "	  var ec_zoneId = '$zone_id';\n";
	$snippet .= "	//]]>--></script>\n";
	$snippet .= "	<div class='ec_externalborder' style='padding:0;border: 1px solid " . $options["external_border_color"] . ";" . ( !empty($options["background_color"]) ? " background-color: " . $options["background_color"] . ";" : "") . " width: " . _get_width_for_style($zone_style) . "px; height: " . _get_height_for_style($zone_style) . "px; position:relative;'>\n";
	$snippet .= "		<div id='$zone_id' class='ec_innerborder' style='border: 3px solid " . $options["internal_border_color"] . ";padding: 3px; height: " . (_get_height_for_style($zone_style) - 12) . "px;'>\n";
	$snippet .= "			<div class='ec_loading' style='padding:0;'>\n";
	$snippet .= "				<img src='http://$ec_server_loc/images/loading.gif' />\n";
	$snippet .= "			</div>\n";
	$snippet .= "			<script type='text/javascript' src='http://$ec_server_loc/ec-load-obs-min.js'></script>\n";
	$snippet .= "			<script type='text/javascript' src='http://$ec_server_loc/ec-apo-min.js'></script>\n";
	$snippet .= "			<div class='ec_footer' style='padding:0;font: normal 10px \"century gothic\",verdana; color: #aaaaaa; position:absolute; bottom:5px; right:5px;text-align:right;' align='right' >" . _get_signoff_for_style($zone_style) . " <a target='_blank'";
	$snippet .= "			href='http://www.echocurrent.com/'" . ( !empty($options["prod_name_font_color"]) ? " style='color:  " . $options["prod_name_font_color"] . "'" : "" ) . ">EchoCurrent</a></div>\n";
	$snippet .= "		</div>\n";
	$snippet .= "	</div>\n";
	$snippet .= "</div>\n";
	return $snippet;
  }
  
  // Private function
  function _get_width_for_style($zone_style) {
  	switch($zone_style) {
  		case 'rectangleText':
  			return 180;
  		case 'mediumRectangleText':
  			return 300;
  		case 'verticalRectangleText':
  			return 240;
  		case 'wideBanner':
  			return 702;
  		case 'fullBanner':
  			return 468;
  		case 'halfBanner':
  			return 234;
  		case 'verticalBanner':
  			return 120;
  		case 'narrowSkyscraper':
  			return 120;
  		case 'skyscraper':
  			return 160;
  		case 'leaderboard':
  			return 728;
  	}
  }
  
  // Private function
  function _get_height_for_style($zone_style) {
  	switch($zone_style) {
  		case 'rectangleText':
  			return 150;
  		case 'mediumRectangleText':
  			return 250;
  		case 'verticalRectangleText':
  			return 400;
  		case 'wideBanner':
  			return 60;
  		case 'fullBanner':
  			return 60;
  		case 'halfBanner':
  			return 60;
  		case 'verticalBanner':
  			return 240;
  		case 'narrowSkyscraper':
  			return 600;
  		case 'skyscraper':
  			return 600;
  		case 'leaderboard':
  			return 92;
  	}
  }
  
  // Private function
  function _get_signoff_for_style($zone_style) {
  	switch($zone_style) {
  		case 'rectangleText':
  		case 'wideBanner':
  		case 'fullBanner':
  		case 'halfBanner':
  		case 'verticalBanner':
  		case 'narrowSkyscraper':
  			return 'by';
  		case 'mediumRectangleText':
  		case 'verticalRectangleText':
  			return 'Products recommended by';
  		case 'skyscraper':
  		case 'leaderboard':
  			return 'Recommended by';
  	}
  }
  
  function init_echocurrent() {
  	register_sidebar_widget('EchoCurrent Affiliate Product Optimizer', 'echocurrent_affiliate_zone');
  	register_widget_control(array('EchoCurrent Affiliate Product Optimizer', 'widgets'), 'echocurrent_affiliate_control', 300, 200);
  	add_shortcode('echocurrent_apo_zone', 'echocurrent_zone_shortcode');
  }
  
  add_action('plugins_loaded', 'init_echocurrent');
?>