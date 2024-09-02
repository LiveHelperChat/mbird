<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmbird/index.tpl.php');

if (erLhcoreClassUser::instance()->hasAccessTo('lhmbird','use_admin')) {
    // WhatsApp
    if ($Params['user_parameters_unordered']['action'] == 'whatsapp' && isset($Params['user_parameters_unordered']['csfr']) && $currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
        \LiveHelperChatExtension\mbird\providers\MBirdLiveHelperChatActivator::installOrUpdate();
        $tpl->set('update',true);
    }

    if ($Params['user_parameters_unordered']['action'] == 'removewhatsapp' && isset($Params['user_parameters_unordered']['csfr']) && $currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
        \LiveHelperChatExtension\mbird\providers\MBirdLiveHelperChatActivator::remove();
        $tpl->set('update',true);
    }
}

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('mbird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Bird.com')
    )
);

?>