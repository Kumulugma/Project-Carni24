<?php
if( !defined('ABSPATH') ){ exit();}
?>
<h1 style="visibility: visible;">WP2Social Auto Publish (V <?php echo xyz_fbap_plugin_get_version(); ?>)</h1>

<div style="width: 99%">
<p style="text-align: justify">
<?php $wp_fbap="WP2Social Auto Publish";
      $fbap_pub_msg=sprintf( __('%s automatically publishes posts from your blog to your Facebook pages. It allows you to filter posts based on post-types and categories. %s is developed and maintained by','facebook-auto-publish'),$wp_fbap,$wp_fbap);
 echo $fbap_pub_msg; ?> <a href="http://xyzscripts.com">XYZScripts</a>.</p>

 

<p style="text-align: justify">
	<?php $fbap_smap_url="https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/features";
	$fbap_smap_plugin = "XYZ Social Media Auto Publish";
	$fbap_feature_msg=sprintf( __('If you would like to have more features , please try <a href="%s" target="_blank">%s</a> which is a premium version of this plugin. We have included a quick comparison of the free and premium plugins for your reference.','facebook-auto-publish'),$fbap_smap_url,$fbap_smap_plugin); 
	echo $fbap_feature_msg; ?>
</p>
 </div>
 <table class="xyz-premium-comparison" cellspacing=0 style="width: 99%;">
	<tr style="background-color: #EDEDED">
		<td><h2> <?php _e('Feature group','facebook-auto-publish'); ?> </h2></td>
		<td><h2> <?php _e('Feature','facebook-auto-publish'); ?> </h2></td>
		<td><h2> <?php _e('Free','facebook-auto-publish'); ?> </h2>
		</td>
		<td><h2> <?php _e('Premium','facebook-auto-publish'); ?> </h2></td>
		<td><h2> <?php  $fbap_smap="SMAP";
		                $fbap_premium_msg=sprintf( __('%s Premium','facebook-auto-publish'),$fbap_smap);
		           echo $fbap_premium_msg; ?>+</h2></td>
	</tr>
	<!-- Supported Media  -->
	<tr>
		<td rowspan="6"><h4> <?php _e('Supported Media','facebook-auto-publish'); ?> </h4></td>
		<td> <?php _e('Facebook','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Twitter','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('LinkedIn','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Instagram','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Tumblr','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Pinterest','facebook-auto-publish'); ?> <span style="color: #FF8000;font-size: 14px;font-weight: bold;">*</span></td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>

		<!-- Posting Options  -->
	<tr>
		<td rowspan="15"><h4> <?php _e('Posting Options','facebook-auto-publish'); ?> </h4></td>
		<td> <?php _e('Publish to facebook pages','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Publish to facebook groups','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Publish to twitter profile','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish to linkedin profile/company pages','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Publish to instagram Business accounts','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Publish to tumblr profile','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish to pinterest boards','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Option to add twitter image description for visually impaired people','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Option to republish existing posts','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish to multiple social media accounts','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Seperate message formats for publishing to multiple social media accounts','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Save auto publish settings of individual posts','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Hash Tags support for Facebook, Twitter, Linkedin, Instagram, Tumblr and Pinterest','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Option to use post tags as hash tags','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Enable/Disable SSL peer verification','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	
	<!-- Image Options  -->
	
	<tr>
	<td rowspan="5"><h4> <?php _e('Image Options','facebook-auto-publish'); ?> </h4></td>
		<td> <?php _e('Publish images along with post content','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	
	<tr>
		<td> <?php _e('Separate default image url for publishing to multiple social media accounts','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to specify preference from featured image, post content, post meta and open graph tags','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Publish multiple images to facebook, tumblr, linkedin and twitter along with post content','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Option to specify multiphoto preference from post content and post meta','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
     <!-- Video Options  -->
	<tr>
	<td rowspan="4"><h4> <?php _e('Video/Audio Options','facebook-auto-publish'); ?> </h4></td>
		<td> <?php _e('Publish video to facebook, tumblr, Linkedin, Instagram and twitter along with post content','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to specify preference from post content, post meta and open graph tags','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Publish audio to tumblr along with post content','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Option to specify audio preference from  post content, post meta and open graph tags','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<!-- Filter Options  -->
		
	<tr>
	<td rowspan="9"><h4> <?php _e('Filter Options','facebook-auto-publish'); ?> </h4></td>
		<td> <?php _e('Filter posts to publish based on categories','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Filter posts to publish based on custom post types','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Filter posts to publish based on sticky posts','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Configuration to enable/disable page publishing','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Category filter for individual accounts','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Custom post type filter for individual accounts','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Enable/Disable page publishing for individual accounts','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Override auto publish scheduling for individual accounts','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Override auto publish based on sticky posts for individual accounts','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<!-- Scheduling  -->
		
	<tr>
	<td rowspan="4"><h4> <?php _e('Scheduling','facebook-auto-publish'); ?> </h4></td>
		<td> <?php _e('Instantaneous post publishing','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Scheduled post publishing using cron','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>

	<tr>
		<td> <?php _e('Status summary of auto publish tasks by mail','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Configurable auto publishing time interval','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	
	
	
	<!-- Publishing History  -->
	<tr>
		<td rowspan="4"><h4> <?php _e('Publishing History','facebook-auto-publish'); ?> </h4></td>
		<td> <?php _e('View auto publish history','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('View auto publish error logs','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to republish post','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to reschedule publishing','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<!-- Installation and Support -->
	<tr>
		<td rowspan="2"><h4> <?php _e('Installation and Support','facebook-auto-publish'); ?> </h4></td>
		<td> <?php _e('Free Installation','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Privilege customer support','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<!-- Addons and Support -->
	<tr>
		<td rowspan="3"><h4> <?php _e('Addon Features','facebook-auto-publish'); ?> </h4></td>
		<td> <?php _e('Advanced Autopublish Scheduler','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		</tr>
		<tr>
		<td> <?php _e('URL-Shortener','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Privilege Management','facebook-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td rowspan="3"><h4> <?php _e('Other','facebook-auto-publish'); ?> </h4></td>
		<td><?php $fbap_smapsolution="SMAPSOLUTIONS"; 
		          $fbap_package_msg=sprintf(__('%s API package for 1 year, worth 10 USD(1 fb account ,10 api calls per hour)','facebook-auto-publish'),$fbap_smapsolution);
		     echo $fbap_package_msg; ?></td>
		<td> <?php _e('1 month free subscription','facebook-auto-publish'); ?>
		</td>
		<td> <?php _e('1 year free subscription','facebook-auto-publish'); ?>
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_FBAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Price','facebook-auto-publish'); ?> </td>
		<td> <?php _e('FREE','facebook-auto-publish'); ?></td>
		<td> <?php _e('Starts from 39 USD','facebook-auto-publish'); ?> </td>
		<td> <?php _e('Starts from 69 USD','facebook-auto-publish'); ?> </td>
	</tr>
	<tr>
		<td> <?php _e('Purchase','facebook-auto-publish'); ?> </td>
		<td></td>
		<td style="padding: 2px" colspan='2' ><a target="_blank"href="https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/purchase"  class="xyz-fbap-buy-button"> <?php _e('Buy Now','facebook-auto-publish'); ?> </a>
		</td>
	</tr>
			
</table>
<br/>
<div style="clear: both;"></div>
<span style="color: #FF8000;font-size: 14px;font-weight: bold;"> * </span> <?php _e('Pinterest is added on experimental basis.','facebook-auto-publish'); ?>

