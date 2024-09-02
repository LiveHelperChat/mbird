<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmbird/channels.tpl.php');

if (ezcInputForm::hasPostData()) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('mbird/channels');
        exit;
    }

    $item = new \LiveHelperChatExtension\mbird\providers\erLhcoreClassModelMBirdChannel();
    
    $Errors = \LiveHelperChatExtension\mbird\providers\MBirdLiveHelperChatValidator::validateChannelSubscribe($item);

    if (count($Errors) == 0) {
        try {

            if (isset($_POST['Subscribe'])) {
                $item->saveThis();
                \LiveHelperChatExtension\mbird\providers\MBirdLiveHelperChatActivator::subscribeToChannel($item);
            } elseif (isset($_POST['UpdateDepartment'])) {
                $item->saveThis();
            } elseif (isset($_POST['UnSubscribe'])) {
                \LiveHelperChatExtension\mbird\providers\MBirdLiveHelperChatActivator::subscribeToChannel($item,'unsubscribe');
                $item->removeThis();
            }

            $tpl->set('updated','true');
        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }
    } else {
        $tpl->set('errors',$Errors);
    }
}

try {
    $channels = \LiveHelperChatExtension\mbird\providers\MBirdLiveHelperChatActivator::getChannels();
    $tpl->set('channels', $channels);
} catch (Exception $e) {
    $tpl->set('errors', [$e->getCode()]);
}

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('mbird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Bird.com')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Channels')
    )
);

?>