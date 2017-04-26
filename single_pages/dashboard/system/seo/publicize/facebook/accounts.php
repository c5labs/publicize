<style>
    .card { padding: 20px 0; }
    .card input { display: inline-block; }
    .card div { display: inline-block; }
    .card .img-div { margin: 0 10px; display: inline-block; vertical-align: middle; width: 50px; height: 50px; background-position: center; background-size: cover;  }
</style>
<div class="row">
    <div class="col-xs-12">
        <p>Publicize allows you to publish to your wall or a page that you manage, please select where you would like us to publish to:</p>
    </div>
    <div class="col-xs-12">
        <hr style="margin: 30px 0; display: block;">
        Publicize to my <strong>Facebook Wall</strong>:
    </div>
</div>
<form action="<?php  echo $this->action('save'); ?>" method="POST">
    <?php echo $this->controller->token->output('save'); ?>
    <div class="row">
        <div class="col-xs-6">
            <div class="card">
                <input type="radio" name="object_id" id="me" value="me" checked="checked">
                <label for="me">
                    <div class="img-div" style="background-image: url(<?php echo $user->getPictureUrl(); ?>);" alt="<?php echo $user->getName(); ?>"></div>
                    <?php echo $user->getName(); ?>
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <hr style="margin: 30px 0; display: block;">
            Publicize to my <strong>Facebook Page</strong>:
        </div>
    </div>
    <div class="row">
    <?php foreach ($pages as $page) { ?>
        <div class="col-xs-6">
            <div class="card">
                <input type="radio" name="object_id" id="<?php echo $page['id']; ?>" value="<?php echo $page['id']; ?>">
                <label for="<?php echo $page['id']; ?>">
                    <div class="img-div" style="background-image: url(<?php echo $page['picture']['data']['url']; ?>);" alt="<?php echo $page['name']; ?>"></div>
                    <?php echo $page['name']; ?>
                </label>
            </div>
        </div>
    <?php } ?>
    </div>
    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <button class="pull-right btn btn-success" type="submit" ><?php  echo t('Save')?></button>
        </div>
    </div>
</form>