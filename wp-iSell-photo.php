<?php
/*
Plugin Name: WP iSell Photo
Version: 1.0.7
Plugin URI: https://wp-ecommerce.net/wp-isell-photo-easily-sell-photos-wordpress-1800
Author: wpecommerce
Author URI: https://wp-ecommerce.net/
Description: A simple plugin to sell photos from WordPress
*/

if(!defined('ABSPATH')) exit;
if(!class_exists('WP_iSELL_PHOTO'))
{
    class WP_iSELL_PHOTO 
    {
        var $plugin_version = '1.0.7';
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
                <div class="update-nag">Please visit the <a target="_blank" href="https://wp-ecommerce.net/wp-isell-photo-easily-sell-photos-wordpress-1800">WP iSell Photo</a> documentation page for usage instructions.</div>
                <?php  
                echo screen_icon().'<h2>WP iSell Photo - v'.$this->plugin_version.'</h2>';
                $plugin_tabs = array(
                    'wp-iSell-photo-settings' => 'General Settings'
                );
                $current = "";
                if(isset($_GET['page'])){
                    $current = $_GET['page'];
                    if(isset($_GET['action'])){
                        $current .= "&action=".$_GET['action'];
                    }
                }
                $content = '';
                $content .= '<h2 class="nav-tab-wrapper">';
                foreach($plugin_tabs as $location => $tabname)
                {
                    if($current == $location){
                        $class = ' nav-tab-active';
                    } else{
                        $class = '';    
                    }
                    $content .= '<a class="nav-tab'.$class.'" href="?page='.$location.'">'.$tabname.'</a>';
                }
                $content .= '</h2>';
                echo $content;
                ?>
                <div id="poststuff"><div id="post-body">

                <div class="postbox">
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
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	/**
	 * Filter the default gallery shortcode output.
	 *
	 * If the filtered output isn't empty, it will be used instead of generating
	 * the default gallery template.
	 *
	 * @since 2.5.0
	 *
	 * @see gallery_shortcode()
	 *
	 * @param string $output The gallery output. Default empty.
	 * @param array  $attr   Attributes of the gallery shortcode.
	 */
        /*
	$output = apply_filters( 'post_gallery', '', $attr );
	if ( $output != '' ) {
		return $output;
	}
        */
	$html5 = current_theme_supports( 'html5', 'gallery' );
	$atts = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => $html5 ? 'figure'     : 'dl',
		'icontag'    => $html5 ? 'div'        : 'dt',
		'captiontag' => $html5 ? 'figcaption' : 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => '',
                'amount'     => '',  // plugin specific parameter
                'button'     => ''   // plugin specific parameter
	), $attr, 'gallery' );
        /* plugin specific check */
        $error_msg = "";
        $paypal_email = get_option('wp_iSell_photo_paypal_email_address');
        $currency = get_option('wp_iSell_photo_paypal_currency_code');
        $return_url = get_option('wp_iSell_photo_paypal_return_url');
        $currency_symbol = get_option('wp_iSell_photo_paypal_currency_symbol');
        $amount = $atts['amount'];
        $button = $atts['button'];
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
        if(!is_numeric($amount))
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
	$id = intval( $atts['id'] );

	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	} else {
		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}
		return $output;
	}

	$itemtag = tag_escape( $atts['itemtag'] );
	$captiontag = tag_escape( $atts['captiontag'] );
	$icontag = tag_escape( $atts['icontag'] );
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) ) {
		$itemtag = 'dl';
	}
	if ( ! isset( $valid_tags[ $captiontag ] ) ) {
		$captiontag = 'dd';
	}
	if ( ! isset( $valid_tags[ $icontag ] ) ) {
		$icontag = 'dt';
	}

	$columns = intval( $atts['columns'] );
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = '';

	/**
	 * Filter whether to print default gallery styles.
	 *
	 * @since 3.1.0
	 *
	 * @param bool $print Whether to print default gallery styles.
	 *                    Defaults to false if the theme supports HTML5 galleries.
	 *                    Otherwise, defaults to true.
	 */
	if ( apply_filters( 'use_default_gallery_style', ! $html5 ) ) {
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
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>\n\t\t";
	}

	$size_class = sanitize_html_class( $atts['size'] );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

	/**
	 * Filter the default gallery shortcode CSS styles.
	 *
	 * @since 2.5.0
	 *
	 * @param string $gallery_style Default CSS styles and opening HTML div container
	 *                              for the gallery shortcode output.
	 */
	$output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {

		$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
		if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
			$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
		} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
			$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
		} else {
			$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
		}
		$image_meta  = wp_get_attachment_metadata( $id );

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
		}
		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
                /* plugin specific code */
                $url = wp_get_attachment_url($attachment->ID);
                $item_name = $attachment->post_title;
                $amount = number_format($amount, 2, '.', '');
                //$item_description = '<div class="wpiSellPhoto_item_description">Item: '.$item_name.'</div>';
                $item_price = '<div class="wpiSellPhoto_item_price">Price: '.$currency_symbol.$amount.'</div>';
                $button_code = wp_iSell_photo_get_button_code_for_paypal($paypal_email,$currency,$return_url,$item_name,$amount,$button);
                $output .= "
                $item_price
                    $button_code
                    ";
                /* end */
		$output .= "</{$itemtag}>";
		if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
			$output .= '<br style="clear: both" />';
		}
	}

	if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
		$output .= "
			<br style='clear: both' />";
	}

	$output .= "
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
        <input type="hidden" name="bn" value="TipsandTricks_SP">    
	$button
	</form>
EOT;
	return $button_code;
}
?>