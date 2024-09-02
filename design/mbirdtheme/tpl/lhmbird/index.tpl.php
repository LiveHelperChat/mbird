<h4><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Bird.com');?></h4>

<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhmbird','configure')) : ?>
<ul>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('mbird/settings')?>"><span class="material-icons">settings</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Settings');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('mbird/channels')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Channels');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('mbird/subscriptions')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Subscriptions');?></a></li>
</ul>
<?php endif;?>

<?php if (isset($update)) : ?>
    <?php $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Settings updated'); ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>

<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhmbird','use_admin')) : ?>
<div class="btn-group mb-2" role="group" aria-label="Basic example">
    <a class="btn btn-sm btn-secondary csfr-required" href="<?php echo erLhcoreClassDesign::baseurl('mbird/index')?>/(action)/whatsapp" >Install/Update WhatsApp Handler</a>
    <a class="btn btn-sm btn-danger csfr-required " data-trans="delete_confirm" href="<?php echo erLhcoreClassDesign::baseurl('mbird/index')?>/(action)/removewhatsapp" >Remove WhatsApp Handler</a>
</div>
<?php endif; ?>

<?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>