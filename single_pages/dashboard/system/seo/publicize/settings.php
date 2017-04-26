<div class="row" style="margin-top: 40px;">
    <div class="col-sm-12">
        <form action="<?php  echo $this->action('save'); ?>" method="POST">
            <?php echo $this->controller->token->output('save'); ?>
            <div class="form-group" id="fileSets">
                <?php echo $form->label('enable_automatic_publishing', t('Enable automatic publishing')); ?>
                <?php echo $form->checkbox('enable_automatic_publishing', true, $enable_automatic_publishing, ['style' => 'margin-left: 10px;']); ?>
            </div>
            <hr>
            <div class="form-group" id="fileSets">
                <?php echo $form->label('enable_logging', t('Enable logging')); ?>
                <?php echo $form->checkbox('enable_logging', true, $enable_logging, ['style' => 'margin-left: 10px;']); ?>
            </div>
            <hr>
            <div class="form-group" id="fileSets">
                <?php echo $form->label('enable_debug_mode', t('Enable debug mode')); ?>
                <?php echo $form->checkbox('enable_debug_mode', true, $enable_debug_mode, ['style' => 'margin-left: 10px;']); ?>
                <div class="help-block">
                    Publicize will not actually share pages, it will only log the attempts.
                </div>
            </div>
            <hr>
            <div class="form-group" id="pageTypes">
                <?php echo $form->label('page_types', t('Automatic publish for page types')); ?>
                <?php echo $form->selectMultiple(
                    'selected_page_types',
                    $page_types,
                    $selected_page_types
                ); ?>
                <script>
                    $(function () {
                        $('select[name="selected_page_types[]"]').select2();
                    });
                </script>
                <div class="help-block">
                    Leaving this empty will automatically post published pages of any page type.
                </div>
            </div>
            <div class="ccm-dashboard-form-actions-wrapper">
                <div class="ccm-dashboard-form-actions">
                    <button class="pull-right btn btn-success" type="submit" ><?php  echo t('Save')?></button>
                </div>
            </div>
        </form>  
    </div>
</div>