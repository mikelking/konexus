<?php
?>
<script type="text/javascript">checked=false;
function checkedAll () {var aa= document.getElementById('shorty');checked = !checked;for (var i =0; i < aa.elements.length; i++) {aa.elements[i].checked = checked;}}</script>

<div>
	<div class='wrap'>
    	<?php screen_icon('options-general'); ?>
    	<h2>Shorty Admin Options</h2>
	</div>
	<div>
	<p><?php _e('Here you may change some settings for your Shorty plugin, although it is not necessary (we already have the optimal settings done for you).'); ?></p>
	
	<form method="post" action="#" id="frm1">
	
	<fieldset>
		<legend><h3><? _e('Master reset "Shorty" to default options.'); ?></h3></legend>
		<p>
		<input type="checkbox" name="shorty_reset" id="shorty_reset" unchecked />
		<label for="shorty_reset"><? _e('Reset ALL settings to default.'); ?> </label>
		</p>
	</fieldset>
	<hr align=left width=660>
	<fieldset>
		<legend><? _e('Autoshrink All on all pages activated.'); ?></legend>
		<p>
		<input type="checkbox" name="auto_shrink" id="auto_shrink" <?=self::render_config_option('auto_shrink'); ?> />
		<label for="auto_shrink"><? _e('Automatically shrink URLs'); ?> </label>
		</p>
		<legend><? _e('Force shrunk urls to HTTPS.'); ?></legend>
		<p>
		<input type="checkbox" name="https_default" id="https_default" <?=self::render_config_option('https_default'); ?> />
		<label for="https_default"><? _e('HTTPS shrink URLs'); ?> </label>
		</p>
	</fieldset>
	<hr align=left width=660>
	<fieldset>
		<legend><? _e('Current Bitly User Id: ' . self::$admin_config['bitly_user_id']); ?>	</legend>
		<p><? _e('The plugin needs to know your bitly user id.',self::$admin_config['bitly_user_id']); ?> </p>
		<p>
		<input type="text" name="bitly_user_id" id="bitly_user_id" value="<?=self::$admin_config['bitly_user_id']?>" />
		</p>
	</fieldset>

	<fieldset>
		<legend><? _e('Current Bitly API key: ' .  self::$admin_config['bitly_api_key']); ?></legend>
		<p><? _e('The plugin needs to know your bitly API key.', self::$admin_config['bitly_api_key']); ?> </p>
		<p>
		<input type="text" size=35 maxlength=35 name="bitly_api_key" id="bitly_api_key" value="<?=self::$admin_config['bitly_api_key']?>" />
		</p>
	</fieldset>
	
	<fieldset>
		<legend><? _e('Current ref tag: ' .  self::$admin_config['default_ref_tag']); ?></legend>
		<p><strong><? _e('It is NOT recommended to change this setting unless you are absolutely certain of what you are doing.', self::$admin_config['default_ref_tag']); ?></strong>
		</p>
		<p>
		<input type="text" name="default_ref_tag" id="default_ref_tag" value="<?=self::$admin_config['default_ref_tag']?>" />
		</p>
	</fieldset>
	<hr align=left width=660>
	<fieldset id="shorty">
		<legend><? _e('Show "Shorty" option on following pages:'); ?></legend>
		<p>
		<input type="checkbox" name="shorty_all" id="shorty_all" onclick="checkedAll();" />
		<label for="shorty_all"><? _e('All pages'); ?> </label>
		</p>
		<p>
		<input type="checkbox" name="shrink_home" id="shrink_home" <?=self::render_config_option('shrink_home'); ?> />
		<label for="shrink_home"><? _e('Index / home'); ?> </label>
		</p>
		<p>
		<input type="checkbox" name="shrink_posts" id="shrink_posts" <?=self::render_config_option('shrink_posts'); ?> />
		<label for="shrink_posts"><? _e('Single post'); ?> </label>
		</p>
		<p>
		<input type="checkbox" name="shrink_categories" id="shrink_categories" <?=self::render_config_option('shrink_categories'); ?> />
		<label for="shrink_categories"><? _e('Category pages'); ?>	</label>
		</p>
		<p>
		<input type="checkbox" name="shrink_pages" id="shrink_pages" <?=self::render_config_option('shrink_categories'); ?> />
		<label for="shrink_pages"><? _e('Standard Pages'); ?> </label>
		</p>	
		<p>
		<input type="checkbox" name="shrink_authors" id="shrink_authors" <?=self::render_config_option('shrink_authors'); ?> />
		<label for="shrink_authors"><? _e('Author Pages'); ?> </label>
		</p>
		<p>
		<input type="checkbox" name="shrink_tags" id="shrink_tags" <?=self::render_config_option('shrink_tags'); ?> />
		<label for="shrink_tags"><? _e('Tag Pages *** not recommended.'); ?> </label>
		</p>
		<p>
	</fieldset>
	
	<?php 
	    wp_nonce_field('shorty_nonce_action', 'shorty_nonce');
	    submit_button(); 
	?>
	</form>
	</div>
</div>