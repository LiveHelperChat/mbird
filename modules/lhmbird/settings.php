<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmbird/settings.tpl.php');

$mbOptions = erLhcoreClassModelChatConfig::fetch('mbird_options');
$data = (array)$mbOptions->data;

if (isset($_POST['StoreOptions'])) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('mbird/settings');
        exit;
    }

    $definition = array(
        'access_key' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'workspace_id' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'organization_id' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'signing_key' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )
    );

    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();

    if ( $form->hasValidData( 'access_key' )) {
        $data['access_key'] = $form->access_key;
    } else {
        $data['access_key'] = '';
    }

    if ( $form->hasValidData( 'workspace_id' )) {
        $data['workspace_id'] = $form->workspace_id;
    } else {
        $data['workspace_id'] = '';
    }
    
    if ( $form->hasValidData( 'organization_id' )) {
        $data['organization_id'] = $form->organization_id;
    } else {
        $data['organization_id'] = '';
    }
    
    if ( $form->hasValidData( 'signing_key' )) {
        $data['signing_key'] = $form->signing_key;
    } else {
        $data['signing_key'] = '';
    }

    $mbOptions->explain = '';
    $mbOptions->type = 0;
    $mbOptions->hidden = 1;
    $mbOptions->identifier = 'mbird_options';
    $mbOptions->value = serialize($data);
    $mbOptions->saveThis();

    $tpl->set('updated','done');
}

$tpl->set('mb_options',$data);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('mbird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Bird.com')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('mbird/settings'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Settings')
    )
);

?>