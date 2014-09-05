=== WP iSell Photo ===
Contributors: wpecommerce
Donate link: https://wp-ecommerce.net/
Tags: sell photos, photo gallery, photography, sell images, sell digital print, commerce, e-commerce, paypal, sell media, stock photos, gallery, image, images, media, photo, photo albums, photos, picture, pictures, thumbnails  
Requires at least: 3.9
Tested up to: 4.0
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily Sell photos, images, digital print etc. using the built-in WordPress gallery feature. Convert your WordPress gallery into a photo store.

== Description ==

WP iSell Photo enhances the functionality of your existing WordPress photo gallery and turns it into an e-commerce photo gallery. It makes  photo selling easier. You don't have to maintain another heavy weight photo gallery plugin for your WordPress blog. This in turn should help you maintain a fast loading site.

= WP iSell Photo Features =

* Sell photos from your WordPress blog easily.
* Increase your photo selling conversion rate with one-click PayPal checkout.
* Create beautiful e-commerce photo gallery on your WordPress blog.
* No advanced technical knowledge required to use this photo selling plugin.

= WP iSell Photo Plugin Usage =

Since WordPress 2.5 there is a new feature in the WordPress media library that allows you to create a gallery of photos/images and add it to a post/page. Lot of users don't even know about this neat little feature of WordPress. WP iSell Photo plugin will help you convert a built in WordPress gallery into a photo selling platform.

**a)** Creating a Photo Gallery in WordPress

Create a new post/page on your WordPress Dashboard. There is an option to upload/insert media. Now select the photos/images from your computer and upload them to the media library. As you upload each image you will see a "Gallery" tab which contains those images. Switch to that tab once you are ready to insert the gallery to your current post/page.

There are some options that you can configure for the gallery you just created (under the "Gallery Settings" section).

1. Link thumbnails to: a) Attachment Page (the page/post you are currently editing) b) Media File c) None
1. Columns: 1 - 9 ( Number of thumbnails in each row)
1. Thumbnail Order: a) Random b) Reverse c) Custom (Drag and drop to reorder images)

Finally hit the "Insert Gallery" button and the gallery will be automatically inserted to your current post/page.

If you want to edit the gallery at any time you can always select the gallery (It looks like a rectangular image in the visual editor) and click on the "Edit" option. Alternatively you can also customize the shortcode for the gallery. You need to switch to the "Text" editor to do it. You will see a shortcode similar to the following:

`[gallery ids="126,125,124,123,122"]`

Here ids parameter represents all the images that are currently present in the gallery.

For more information on how to customize the WordPress gallery shortcode please refer to the [WordPress Documentation](http://codex.wordpress.org/Gallery_Shortcode)

**b)** WP iSell Photo Settings

There are some options that you need to configure in the General Settings of the plugin before your site goes live. On your *WordPress Dashboard* under *Settings* click on the *WP iSell Photo* option. It will take you to the Settings page.

* PayPal Email Address: Your PayPal email address
* PayPal Currency: The currency code (e.g. USD, GBP etc)
* Currency Symbol: The symbol for your currency code (e.g. $). It's for display purpose only.
* Return URL: The URL where your customer will be redirected to after a successful payment

**c)** Creating Buy Now buttons to sell photos

Go to the post/page where you already have an existing gallery embedded. Add an additional `amount` parameter to the gallery shortcode and specify the price in it. For example:

`[gallery amount="5.00" ids="126,125,124,123,122"]`

now each image of the current gallery will have a "Buy Now" button with price 5.00

You can also customize the look and feel of the "Buy Now" button. Simply include a `button` parameter in the gallery shortcode and specify the text you want to use for the button. For example:

`[gallery amount="3.99" button="Buy it Now" ids="126,125,124,123,122"]`

If you want to use an image for the button you can do so by specifying the URL in the `button` parameter. For example:

`[gallery amount="3.99" button="http://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" ids="126,125,124,123,122"]`

For detailed documentation please visit the [WordPress iSell Photo](https://wp-ecommerce.net/wp-isell-photo-easily-sell-photos-wordpress-1800) plugin page

== Installation ==

1. Go to the Add New plugins screen in your WordPress admin area
1. Click the upload tab
1. Browse for the plugin file (wp-iSell-photo.zip)
1. Click Install Now and then activate the plugin
1. Now, embed a gallery on a post/page to sell photos from your WordPress blog.

== Frequently Asked Questions ==

= Can this plugin be used to sell photos in WordPress =

Yes.

= Can this plugin be used to sell WordPress media library images? =

Yes.

== Screenshots ==

For screenshots please visit the [WordPress Sell Photo](https://wp-ecommerce.net/wp-isell-photo-easily-sell-photos-wordpress-1800) plugin page

== Upgrade Notice ==
None

== Changelog ==

= 1.0.5 =
* Plugin now works with WordPress 3.9 gallery features

= 1.0.4 =
* Plugin now works with WordPress 3.8

= 1.0.3 =
* Plugin is now compatible with WordPress 3.7

= 1.0.2 =
* Plugin is now compatible with the gallery options of WordPress 3.6
* Caption is supported for each gallery image

= 1.0.1 =
* Plugin is now compatible with the gallery options of WordPress 3.5

= 1.0.0 =
* First commit
