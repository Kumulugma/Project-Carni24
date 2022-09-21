<?php 
if( !defined('ABSPATH') ){ exit();}
?>
<div >


	<form method="post" name="xyz_fbap_logs_form">
		<fieldset
			style="width: 99%; border: 1px solid #F7F7F7; padding: 10px 0px;">
			


<div style="text-align: left;padding-left: 7px;"><h3> <?php _e('Auto Publish Logs','facebook-auto-publish'); ?> </h3></div>
		<span> <?php _e('Last ten logs','facebook-auto-publish'); ?> </span>
		   <table class="widefat" style="width: 99%; margin: 0 auto; border-bottom:none;">
				<thead>
					<tr class="xyz_fbap_log_tr">
						<th scope="col" width="1%">&nbsp;</th>
						<th scope="col" width="12%"> <?php _e('Post Id','facebook-auto-publish'); ?> </th>
						<th scope="col" width="12%"> <?php _e('Post Title','facebook-auto-publish'); ?> </th>
						<th scope="col" width="18%"> <?php _e('Published On','facebook-auto-publish'); ?> </th>
						<th scope="col" width="15%"> <?php _e('Status','facebook-auto-publish'); ?> </th>
					</tr>
					</thead>
					<?php 
					
					$post_fb_logsmain = get_option('xyz_fbap_post_logs' );

					if(is_array($post_fb_logsmain))
					{
					$post_fb_logsmain_array = array();
           				foreach ($post_fb_logsmain as $logkey => $logval)
					{
						$post_fb_logsmain_array[]=$logval;
						
					}
                                        
                                         					
					if($post_fb_logsmain=='')
					{
						?>
						<tr><td colspan="4" style="padding: 5px;"> <?php _e('No logs Found','facebook-auto-publish'); ?> </td></tr>
						<?php 
					}
									
					if(is_array($post_fb_logsmain_array))
					{
						for($i=9;$i>=0;$i--)
						{
							if(array_key_exists($i,$post_fb_logsmain_array)){
							if(($post_fb_logsmain_array[$i])!='')//if(array_key_exists($i,$post_fb_logsmain_array))
							{
								$post_fb_logs=$post_fb_logsmain_array[$i];
								$postid=$post_fb_logs['postid'];
								$publishtime=$post_fb_logs['publishtime'];
								if($publishtime!="")
									$publishtime=xyz_fbap_local_date_time('Y/m/d g:i:s A',$publishtime);
								$status=$post_fb_logs['status'];
							
								?>
								<tr>
									<td>&nbsp;</td>
									<td  style="vertical-align: middle !important;padding: 5px;">
									<?php echo $postid;	?>
									</td>
									<td  style="vertical-align: middle !important;padding: 5px;">
									<?php echo get_the_title($postid);	?>
									</td>
										
									<td style="vertical-align: middle !important;padding: 5px;">
									<?php echo $publishtime;?>
									</td>
									
									<td class='xyz_fbap_td_custom' style="vertical-align: middle !important;padding: 5px;">
									<?php
									
								  	if($status=="1"){
										echo "<span style=\"color:green\">Success</span>";
								  	}
									else if($status=="0")
										echo '';
									else
									{
										$arrval=unserialize($status);
										foreach ($arrval as $a=>$b)
											echo $b;
									}
									?>
									</td>
								</tr>
								<?php  
								}}
						  }								
					}
}
					?>
				
           </table>
			
		</fieldset>

	</form>
<div style="padding: 5px;color: #e67939;font-size: 14px;"> <?php _e('For publishing a simple text message, it will take 1 API call, Upload image option will take 2-3 API calls and attach link option take 1 API call(2 api calls, if enabled option for clearing cache).','facebook-auto-publish'); ?></div>
</div>
				