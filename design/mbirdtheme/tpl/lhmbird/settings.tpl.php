<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Integration settings'); ?></h1>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Workspace ID'); ?></label>
        <input type="text" name="workspace_id" class="form-control form-control-sm" value="<?php isset($mb_options['workspace_id']) ? print htmlspecialchars($mb_options['workspace_id']) : ''?>" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Access Key'); ?></label>
        <input type="text" name="access_key" class="form-control form-control-sm" value="<?php isset($mb_options['access_key']) ? print htmlspecialchars($mb_options['access_key']) : ''?>" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Organization ID'); ?></label>
        <input type="text" name="organization_id" class="form-control form-control-sm" value="<?php isset($mb_options['organization_id']) ? print htmlspecialchars($mb_options['organization_id']) : ''?>" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Signing Key'); ?></label>
        <input type="text" name="signing_key" class="form-control form-control-sm" value="<?php isset($mb_options['signing_key']) ? print htmlspecialchars($mb_options['signing_key']) : ''?>" />
    </div>

    <?php $incomingWebhook = erLhcoreClassModelChatIncomingWebhook::findOne(array('filter' => array('name' => 'BirdAppWhatsApp')));
    if (is_object($incomingWebhook)) : ?>
        <div class="text-muted"><a href="<?php echo erLhcoreClassDesign::baseurl('webhooks/editincoming')?>/<?php echo $incomingWebhook->id?>"><span class="badge bg-info"><?php echo htmlspecialchars($incomingWebhook->name)?></span></a> Change default department</div>
    <?php endif; ?>

    <button name="StoreOptions" class="btn btn-sm btn-secondary" type="submit"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Save'); ?></button>

</form>