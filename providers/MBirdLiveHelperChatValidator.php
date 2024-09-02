<?php

namespace LiveHelperChatExtension\mbird\providers;

class MBirdLiveHelperChatValidator {

    public static function validateChannelSubscribe(& $item) {
        $definition = array(
            'dep_id' => new \ezcInputFormDefinitionElement(
                \ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
            ),
            'channel_id' => new \ezcInputFormDefinitionElement(
                \ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'name' => new \ezcInputFormDefinitionElement(
                \ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            )
        );

        $form = new \ezcInputForm( INPUT_POST, $definition );
        $Errors = array();

        if ($form->hasValidData( 'channel_id' ) && $form->channel_id != '') {
            $item->channel_id = $form->channel_id;
        } else {
            $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Channel ID is required!');
        }

        if ($form->hasValidData( 'name' ) && $form->name != '') {
            $item->name = $form->name;
        } else {
            $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Name is required!');
        }

        if ( $form->hasValidData( 'dep_id' )) {
            $item->dep_id = $form->dep_id;
        } else {
            $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Please choose a department!');
        }

        $subscriptionPresent = erLhcoreClassModelMBirdChannel::findOne(['filter' => ['channel_id' => $item->channel_id]]);

        if (is_object($subscriptionPresent)) {
            $item->id = $subscriptionPresent->id;
        }

        return $Errors;
    }

}
