<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<style>
    .provider h2 { margin: 5px 0 0; }
    .provider .provider-status { color: green; font-weight: bold; margin: 10px 0 20px; }
    .provider-inactive { color: #999; }
    .provider.provider-inactive .provider-status { color: #999; font-weight: normal; }
}
</style>
<div class="row">
    <div class="col-xs-12">
        <p>Publicize allows you to automatically publish pages &amp; posts to your Twitter or Facebook feed. You can setup your social media accounts and tweak the addons settings from this page.</p>
    </div>
    <div class="col-xs-12">
        <h3 style="margin-top: 30px;">Accounts</h3>
        <hr style="margin: 30px 0; display: block;">
    </div>
</div>
<div class="row">
    <div class="col-md-6 text-center provider <?php if (!isset($twitter)) { ?>provider-inactive<?php } ?>">
        <i class="fa fa-twitter fa-5x"></i>
        <h2>Twitter</h2>
        <?php if (!isset($twitter)) { ?>
            <div class="provider-status">Not connected</div>
            <a href="<?php echo View::url('/dashboard/system/seo/publicize/twitter'); ?>" class="btn btn-primary">Connect</a>
        <?php } else { ?>
            <div class="provider-status">Connected as <?php echo $twitter['connected_as']; ?></div>
            <a href="<?php echo View::url('/dashboard/system/seo/publicize/twitter'); ?>" class="btn btn-primary">Re-connect</a>
            <a href="<?php echo View::url('/dashboard/system/seo/publicize/disconnect/?provider=publicize-twitter&ccm_token='.$this->controller->token->generate('disconnect')); ?>" class="btn btn-default" onclick="return confirm('Are you sure you want to disconnect Twitter?');">Disconnect</a>
        <?php } ?>
    </div>
    <div class="col-md-6 text-center provider <?php if (!isset($facebook)) { ?>provider-inactive<?php } ?>">
        <i class="fa fa-facebook fa-5x"></i>
        <h2>Facebook</h2>
        <?php if (!isset($facebook)) { ?>
            <div class="provider-status">Not connected</div>
            <a href="<?php echo View::url('/dashboard/system/seo/publicize/facebook'); ?>" class="btn btn-primary">Connect</a>
        <?php } else { ?>
            <div class="provider-status">Connected as <?php echo $facebook['connected_as']; ?></div>
            <a href="<?php echo View::url('/dashboard/system/seo/publicize/facebook'); ?>" class="btn btn-primary">Re-connect</a>
            <a href="<?php echo View::url('/dashboard/system/seo/publicize/disconnect/?provider=publicize-facebook&ccm_token='.$this->controller->token->generate('disconnect')); ?>" class="btn btn-default" onclick="return confirm('Are you sure you want to disconnect Facebook?');">Disconnect</a>
        <?php } ?>
    </div>
</div>
<div class="row" style="margin-top: 35px;">
    <div class="col-xs-12">
        <h3>Publishing Settings</h3>
        <hr style="margin: 30px 0; display: block;">
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <span>Automatic publishing enabled:</span>
        <strong style="display: inline-block; margin-left: 10px;"><?php echo isset($enable_automatic_publishing) ? 'Yes' : 'No'; ?></strong> 
    </div>
    <div class="col-xs-12" style="margin-top: 10px;">
        <span>Page types to automatically publish to your accounts:</span>
        <strong style="display: inline-block; margin-left: 10px;"><?php echo isset($selected_page_types) ? $selected_page_types : 'All Page Types'; ?></strong> 
    </div>
    <div class="col-xs-12" style="margin-top: 10px;">
        <span>Logging enabled:</span>
        <strong style="display: inline-block; margin-left: 10px;"><?php echo isset($enable_logging) ? 'Yes' : 'No'; ?></strong> 
    </div>
    <div class="col-xs-12" style="margin-top: 10px;">
        <span>Debug mode:</span>
        <strong style="display: inline-block; margin-left: 10px;"><?php echo isset($enable_debug_mode) ? 'Yes' : 'No'; ?></strong> 
    </div>
    <div class="col-xs-12" style="margin-top: 30px;">
        <a href="<?php echo View::url('/dashboard/system/seo/publicize/settings'); ?>" class="btn btn-primary">Change Settings</a>
    </div>
</div>