<?php
/*
Plugin Name: WP iSell Photo
Version: 1.0.1
Plugin URI: http://wp-ecommerce.net/?p=1800
Author: wpecommerce
Author URI: http://wp-ecommerce.net/
Description: A simple plugin to sell photos from WordPress
*/

if(!defined('ABSPATH')) exit;
if(!class_exists('WP_iSELL_PHOTO'))
{
	class WP_iSELL_PHOTO 
	{
		var $plugin_version = '1.0.1';
		function __construct() 
		{
			define('WP_iSELL_PHOTO_VERSION', $this->plugin_version);
			$this->plugin_includes();
		}
		function plugin_includes()
		{
			if(is_admin( ) ) 
			{
				add_filter('plugin_action_links', array(&$this,'wp_iSell_photo_plugin_action_links'), 10, 2 );
			}
			add_action('admin_menu', array( &$this, 'wp_iSell_photo_add_options_menu' ));
			add_filter('post_gallery', 'wp_iSell_photo_gallery_shortcode', 10, 2 );	
		}
		function plugin_url() 
		{
			if($this->plugin_url) return $this->plugin_url;
			return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
		}
		function wp_iSell_photo_plugin_action_links($links, $file) 
		{
			if ( $file == plugin_basename( dirname( __FILE__ ) . '/wp-iSell-photo.php' ) ) 
			{
				$links[] = '<a href="options-general.php?page=wp-iSell-photo-settings">Settings</a>';
			}
			return $links;
		}
		
		function wp_iSell_photo_add_options_menu()
		{
			if(is_admin())
			{
				add_options_page('WP iSell Photo Settings', 'WP iSell Photo', 'manage_options', 'wp-iSell-photo-settings', array(&$this, 'wp_iSell_photo_options_page'));
			}
			add_action('admin_init', array(&$this, 'wp_iSell_photo_add_settings'));		
		}
		function wp_iSell_photo_add_settings()
		{ 
			register_setting('wp-iSell-photo-settings-group', 'wp_iSell_photo_paypal_email_address');
			register_setting('wp-iSell-photo-settings-group', 'wp_iSell_photo_paypal_currency_code');
			register_setting('wp-iSell-photo-settings-group', 'wp_iSell_photo_paypal_currency_symbol');
			register_setting('wp-iSell-photo-settings-group', 'wp_iSell_photo_paypal_return_url');	
		}
		function wp_iSell_photo_options_page()
		{
			
			?>
			<div class="wrap">
			<div id="poststuff"><div id="post-body">
			
			<h2>WP iSell Photo - v<?php echo $this->plugin_version;?></h2>
			<div class="postbox">
			<h3><label for="title">General Settings</label></h3>
			<div class="inside">		
			<form method="post" action="options.php">
			    <?php settings_fields('wp-iSell-photo-settings-group'); ?>
			    <table class="form-table">
			        <tr valign="top">
			        <th scope="row">PayPal Email Address</th>
			        <td><input type="text" name="wp_iSell_photo_paypal_email_address" size="60" value="<?php echo get_option('wp_iSell_photo_paypal_email_address'); ?>" />
			        <p><i>Your PayPal email address</i></p>
			        </td>
			        </tr>
			         
			        <tr valign="top">
			        <th scope="row">PayPal Currency</th>
			        <td><input type="text" name="wp_iSell_photo_paypal_currency_code" size="40" value="<?php echo get_option('wp_iSell_photo_paypal_currency_code'); ?>" />
			        <p><i>Your PayPal Currency (Example: USD, EUR, GBP, AUD etc)</i></p>
			        </td>
			        </tr>
			        
			        <tr valign="top">
			        <th scope="row">Currency Symbol</th>
			        <td><input type="text" name="wp_iSell_photo_paypal_currency_symbol" size="20" value="<?php echo get_option('wp_iSell_photo_paypal_currency_symbol'); ?>" />
			        <p><i>(Example: $)</i></p>
			        </td>
			        </tr>
			        
			        <tr valign="top">
			        <th scope="row">Return URL</th>
			        <td><input type="text" name="wp_iSell_photo_paypal_return_url" size="80" value="<?php echo get_option('wp_iSell_photo_paypal_return_url'); ?>" />
			        <p><i>The url where your customer will be redirected to after a successful payment</i></p>
			        </td>
			        </tr>
			    </table>
			    
			    <p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			    </p>		
			</form>
			</div></div>
			
			</div></div>
			</div>
			<?php
		}
	}
	$GLOBALS['wp_iSell_photo'] = new WP_iSELL_PHOTO();
}

function wp_iSell_photo_gallery_shortcode($output,$attr) 
{
    $post = get_post();

    static $instance = 0;
    $instance++;

    if ( ! empty( $attr['ids'] ) ) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if ( empty( $attr['orderby'] ) )
            $attr['orderby'] = 'post__in';
        $attr['include'] = $attr['ids'];
    }

    // Allow plugins/themes to override the default gallery template.
    /*
    $output = apply_filters('post_gallery', '', $attr);
    if ( $output != '' )
        return $output;
    */
    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post->ID,
        'itemtag'    => 'dl',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'columns'    => 3,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => '',
        'amount'	 => '',  // plugin specific parameter
        'button'	 => ''   // plugin specific parameter
    ), $attr));
    /* plugin specific check */
    $error_msg = "";
    $paypal_email = get_option('wp_iSell_photo_paypal_email_address');
    $currency = get_option('wp_iSell_photo_paypal_currency_code');
    $return_url = get_option('wp_iSell_photo_paypal_return_url');
    $currency_symbol = get_option('wp_iSell_photo_paypal_currency_symbol');
    if(empty($paypal_email))
    {
        $error_msg .= '<div style="color:red;">You did not specify a PayPal email address in the settings</div>';
    }
    if(empty($currency))
    {
        $error_msg .= '<div style="color:red;">You did not specify a currency code in the settings</div>';
    }
    if(empty($currency_symbol))
    {
        $error_msg .= '<div style="color:red;">You did not specify a currency symbol in the settings</div>';
    }
    if(empty($return_url))
    {
        $error_msg .= '<div style="color:red;">You did not specify a return url in the settings</div>';
    }
    if(empty($amount))
    {
        $error_msg .= '<div style="color:red;">You did not specify a price in the shortcode</div>';
    }
    if(empty($button))
    {
        $button = "Buy Now";
    }
    if(!empty($error_msg))
    {
        return $error_msg;
    }
    /* end check */
    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if ( empty($attachments) )
        return '';

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $columns = intval($columns);
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $gallery_style = $gallery_div = '';
    if ( apply_filters( 'use_default_gallery_style', true ) )
        $gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->";
    $size_class = sanitize_html_class( $size );
    $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
    $output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
        $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
        /* plugin specific code */
        $url = wp_get_attachment_url($attachment->ID);
		$item_name = $attachment->post_title;
		$amount = number_format($amount, 2, '.', '');
		//$item_description = '<div class="wpiSellPhoto_item_description">Item: '.$item_name.'</div>';
		$item_price = '<div class="wpiSellPhoto_item_price">Price: '.$currency_symbol.$amount.'</div>';
		$button_code = wp_iSell_photo_get_button_code_for_paypal($paypal_email,$currency,$return_url,$item_name,$amount,$button);
        /* end */
        $output .= "<{$itemtag} class='gallery-item'>";
        $output .= "
			<{$icontag} class='gallery-icon'>
				$link
				$item_price
				$button_code
			</{$icontag}>";
        if ( $captiontag && trim($attachment->post_excerpt) ) {
            $output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
        }
        $output .= "</{$itemtag}>";
        if ( $columns > 0 && ++$i % $columns == 0 )
            $output .= '<br style="clear: both" />';
    }

    $output .= "
			<br style='clear: both;' />
		</div>\n";

    return $output;
}

function wp_iSell_photo_get_button_code_for_paypal($paypal_email,$currency,$return_url,$item_name,$amount,$button)
{
	$image_button = strstr($button, 'http');
	if($image_button==FALSE)
	{
		$button = '<input type="submit" class="wpiSellPhoto_buy_now_button" value="'.$button.'">';	
	}
	else
	{
		$button = '<input type="image" src="'.$button.'" border="0" name="submit" alt="'.$item_name.'">';
	}
	$button_code = <<<EOT
	<form method="post" action="https://www.paypal.com/cgi-bin/webscr">
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="business" value="$paypal_email">
	<input type="hidden" name="item_name" value="$item_name">
	<input type="hidden" name="amount" value="$amount">
	<input type="hidden" name="currency_code" value="$currency">
	<input type="hidden" name="return" value="$return_url">
	$button
	</form>
EOT;
	return $button_code;
}
?>