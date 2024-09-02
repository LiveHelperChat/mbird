<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhmbird','use_admin')) : ?>
    <li class="nav-item"><a class="nav-link" href="<?php echo erLhcoreClassDesign::baseurl('mbird/index')?>"><i class="material-icons">send</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Bird.com');?></a></li>
<?php endif; ?>