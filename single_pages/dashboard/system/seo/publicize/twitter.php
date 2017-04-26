<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<div class="row">
    <div class="col-sm-12">
        <p>To publish to your Twitter account you will need an application id and secret, you can find a video of how to obtain these at the bottom of this page. Once you have these, please enter them below and click 'Connect Account'.</p>
    </div>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-sm-12">
        <form action="<?php  echo $this->action('authorize'); ?>" method="POST">
            <?php echo $this->controller->token->output('authorize'); ?>
            <div class="form-group" id="fileSets">
                <?php echo $form->label('publicize_twitter[identifier]', t('Twitter Application ID')); ?>
                <?php echo $form->text('publicize_twitter[identifier]', $publicize_twitter['identifier']); ?>
            </div>
            <div class="form-group" id="fileSets">
                <?php echo $form->label('publicize_twitter[secret]', t('Twitter Application Secret')); ?>
                <?php echo $form->text('publicize_twitter[secret]', $publicize_twitter['secret']); ?>
            </div>
            <button type="submit" class="btn btn-primary">Connect Account</button>
        </form>  
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
    <hr style="margin: 40px 0;">
    <h3>How to setup a Twitter application</h3>
    <p>The video below walks through how to obtain an application ID &amp; secret from Twitter.</p>
    <iframe width="854" height="480" src="https://www.youtube.com/embed/9ckccMDhtQI" frameborder="0" allowfullscreen style="margin-top: 20px;"></iframe>
    </div>
</div>