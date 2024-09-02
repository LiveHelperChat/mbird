<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmbird/subscriptions.tpl.php');

try {
    $subscriptions = \LiveHelperChatExtension\mbird\providers\MBirdLiveHelperChatActivator::getSubscriptions();
    $tpl->set('subscriptions', $subscriptions);
} catch (Exception $e) {
    $tpl->set('errors', [$e->getMessage()]);
}

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('mbird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Bird.com')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Subscriptions')
    )
);

?>