<?php

namespace LiveHelperChatExtension\mbird\providers;
class MBirdLiveHelperChatActivator {

    public static function remove()
    {
        if ($incomingWebhook = \erLhcoreClassModelChatIncomingWebhook::findOne(['filter' => ['name' => 'BirdAppWhatsApp']])) {
            $incomingWebhook->removeThis();
        }

        if ($restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'BirdAppWhatsApp']])) {
            $restAPI->removeThis();
        }

        if ($botPrevious = \erLhcoreClassModelGenericBotBot::findOne(['filter' => ['name' => 'BirdAppWhatsApp']])) {
            $botPrevious->removeThis();

            if ($event = \erLhcoreClassModelChatWebhook::findOne(['filter' => ['event' => ['chat.desktop_client_admin_msg', 'bot_id' => $botPrevious->id]]])) {
                $event->removeThis();
            }

            if ($event = \erLhcoreClassModelChatWebhook::findOne(['filter' => ['event' => ['chat.workflow.canned_message_before_save', 'bot_id' => $botPrevious->id]]])) {
                $event->removeThis();
            }

            if ($event = \erLhcoreClassModelChatWebhook::findOne(['filter' => ['event' => ['chat.web_add_msg_admin', 'bot_id' => $botPrevious->id]]])) {
                $event->removeThis();
            }

            if ($event = \erLhcoreClassModelChatWebhook::findOne(['filter' => ['event' => ['chat.before_auto_responder_msg_saved', 'bot_id' => $botPrevious->id]]])) {
                $event->removeThis();
            }
        }
    }

    public static function installOrUpdate($paramsActivation = [])
    {
        // GoogleBusinessMessage
        $incomingWebhook = \erLhcoreClassModelChatIncomingWebhook::findOne(['filter' => ['name' => 'BirdAppWhatsApp']]);

        $incomingWebhookContent = file_get_contents('extension/mbird/doc/whatsapp/incoming-webhook.json');
        $content = json_decode($incomingWebhookContent,true);

        if (!$incomingWebhook) {
            $incomingWebhook = new \erLhcoreClassModelChatIncomingWebhook();
            $incomingWebhook->setState($content);
            $incomingWebhook->dep_id = isset($paramsActivation['dep_id']) && $paramsActivation['dep_id'] > 0 ? $paramsActivation['dep_id'] : 1;
            $incomingWebhook->name = 'BirdAppWhatsApp';
            $incomingWebhook->identifier = \erLhcoreClassModelForgotPassword::randomPassword(20);
        } else {
            $dep_id = $incomingWebhook->dep_id;
            $identifier = $incomingWebhook->identifier;
            $incomingWebhook->setState($content);
            $incomingWebhook->dep_id = $dep_id;
            $incomingWebhook->identifier = $identifier;
            $incomingWebhook->name = 'BirdAppWhatsApp';
        }
        $incomingWebhook->saveThis();

        // RestAPI
        $restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'BirdAppWhatsApp']]);
        $content = json_decode(file_get_contents('extension/mbird/doc/whatsapp/rest-api.json'),true);

        if (!$restAPI) {
            $restAPI = new \erLhcoreClassModelGenericBotRestAPI();
        }

        $restAPI->setState($content);
        $restAPI->name = 'BirdAppWhatsApp';
        $restAPI->saveThis();

        if ($botPrevious = \erLhcoreClassModelGenericBotBot::findOne(['filter' => ['name' => 'BirdAppWhatsApp']])) {
            $botPrevious->removeThis();
        }

        $botData = \erLhcoreClassGenericBotValidator::importBot(json_decode(file_get_contents('extension/mbird/doc/whatsapp/bot-data.json'),true));
        $botData['bot']->name = 'BirdAppWhatsApp';
        $botData['bot']->updateThis(['update' => ['name']]);

        $trigger = $botData['triggers'][0];
        $actions = $trigger->actions_front;
        $actions[0]['content']['rest_api'] = $restAPI->id;
        $trigger->actions_front = $actions;
        $trigger->actions = json_encode($actions);
        $trigger->updateThis(['update' => ['actions']]);

        if ($botPrevious && $event = \erLhcoreClassModelChatWebhook::findOne(['filter' => ['event' => ['chat.desktop_client_admin_msg', 'bot_id' => $botPrevious->id]]])) {
            $event->removeThis();
        }
        $event = new \erLhcoreClassModelChatWebhook();
        $event->setState(json_decode(file_get_contents('extension/mbird/doc/whatsapp/chat.desktop_client_admin_msg.json'),true));
        $event->bot_id = $botData['bot']->id;
        $event->trigger_id = $trigger->id;
        $event->saveThis();

        if ($botPrevious && $event = \erLhcoreClassModelChatWebhook::findOne(['filter' => ['event' => ['chat.workflow.canned_message_before_save', 'bot_id' => $botPrevious->id]]])) {
            $event->removeThis();
        }
        $event = new \erLhcoreClassModelChatWebhook();
        $event->setState(json_decode(file_get_contents('extension/mbird/doc/whatsapp/chat.workflow.canned_message_before_save.json'),true));
        $event->bot_id = $botData['bot']->id;
        $event->trigger_id = $trigger->id;
        $event->saveThis();

        if ($botPrevious && $event = \erLhcoreClassModelChatWebhook::findOne(['filter' => ['event' => ['chat.web_add_msg_admin', 'bot_id' => $botPrevious->id]]])) {
            $event->removeThis();
        }
        $event = new \erLhcoreClassModelChatWebhook();
        $event->setState(json_decode(file_get_contents('extension/mbird/doc/whatsapp/chat.web_add_msg_admin.json'),true));
        $event->bot_id = $botData['bot']->id;
        $event->trigger_id = $trigger->id;
        $event->saveThis();

        if ($botPrevious && $event = \erLhcoreClassModelChatWebhook::findOne(['filter' => ['event' => ['chat.before_auto_responder_msg_saved', 'bot_id' => $botPrevious->id]]])) {
            $event->removeThis();
        }
        $event = new \erLhcoreClassModelChatWebhook();
        $event->setState(json_decode(file_get_contents('extension/mbird/doc/whatsapp/chat.before_auto_responder_msg_saved.json'),true));
        $event->bot_id = $botData['bot']->id;
        $event->trigger_id = $trigger->id;
        $event->saveThis();
    }

    public static function getSubscriptions()
    {
        $mbOptions = \erLhcoreClassModelChatConfig::fetch('mbird_options');
        $data = (array)$mbOptions->data;
        return self::getRestAPI([
            'base_url' => 'https://api.bird.com',
            'method' => 'organizations/'.$data['organization_id'].'/workspaces/'.$data['workspace_id'].'/webhook-subscriptions' ,
            'headers' => ['Authorization: AccessKey ' . $data['access_key']],
            'log_response' => true,
        ]);
    }

    public static function getChannels()
    {
        $mbOptions = \erLhcoreClassModelChatConfig::fetch('mbird_options');
        $data = (array)$mbOptions->data;
        return self::getRestAPI([
            'base_url' => 'https://api.bird.com',
            'method' => 'workspaces/'.$data['workspace_id'].'/channels' ,
            'headers' => ['Authorization: AccessKey ' . $data['access_key']],
            'log_response' => true,
        ]);
    }

    public static function subscribeToChannel($item, $action = 'subscribe')
    {
        if ($action === 'unsubscribe') {
            $subscriptions = self::getSubscriptions();
        }
        
        $mbOptions = \erLhcoreClassModelChatConfig::fetch('mbird_options');
        $data = (array)$mbOptions->data;

        $incomingWebhook = \erLhcoreClassModelChatIncomingWebhook::findOne(array('filter' => array('name' => 'BirdAppWhatsApp')));

        foreach (['whatsapp.inbound','whatsapp.outbound','whatsapp.interaction'] as $event) {
            if ($action === 'subscribe') {
                self::getRestAPI([
                    'base_url' => 'https://api.bird.com',
                    'method' => 'organizations/' . $data['organization_id'] . '/workspaces/' . $data['workspace_id'] . '/webhook-subscriptions',
                    'body_json' => json_encode([
                        'signingKey' => $data['signing_key'],
                        "service" => "channels",
                        "event" => $event,
                        "url" =>  \erLhcoreClassSystem::getHost() . \erLhcoreClassDesign::baseurldirect('webhooks/incoming') . '/' . $incomingWebhook->identifier,
                        "eventFilters" => [
                            [
                                "key" => "channelId",
                                "value" => $item->channel_id
                            ]
                        ]
                    ]),
                    'headers' => ['Authorization: AccessKey ' . $data['access_key']],
                    'log_response' => true,
                ]);
            } elseif (isset($subscriptions['results']) && !empty($subscriptions['results'])) {
                foreach ($subscriptions['results'] as $subscription) {
                    if (
                        $subscription['organizationId'] === $data['organization_id'] &&
                        $subscription['workspaceId'] === $data['workspace_id'] &&
                        $subscription['service'] === 'channels' &&
                        $subscription['event'] === $event &&
                        $subscription['eventFilters'][0]['key'] === 'channelId' &&
                        $subscription['eventFilters'][0]['value'] === $item->channel_id
                    ) {
                        self::getRestAPI([
                            'base_url' => 'https://api.bird.com',
                            'method' => 'organizations/' . $data['organization_id'] . '/workspaces/' . $data['workspace_id'] . '/webhook-subscriptions/' . $subscription['id'],
                            'headers' => ['Authorization: AccessKey ' . $data['access_key']],
                            'log_response' => true,
                            'method_type' => 'delete',
                        ]);
                    }
                }
            }
        }
    }

    public static $lastCallDebug = array();
    public static $apiTimeout = 40;

    public static function getRestAPI($params)
    {
        $try = isset($params['try']) ? $params['try'] : 3;

        for ($i = 0; $i < $try; $i++) {

            $ch = curl_init();
            $url = rtrim($params['base_url'], '/') . '/' . $params['method'] . (isset($params['args']) ? '?' . http_build_query($params['args']) : '');

            if (!isset(self::$lastCallDebug['request_url'])) {
                self::$lastCallDebug['request_url'] = array();
            }

            if (!isset(self::$lastCallDebug['request_url_response'])) {
                self::$lastCallDebug['request_url_response'] = array();
            }

            self::$lastCallDebug['request_url'][] = $url;

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, (isset($params['api_timeout']) ? $params['api_timeout'] : self::$apiTimeout));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, (isset($params['api_connect_timeout']) ? $params['api_connect_timeout'] : 15));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            if (isset($params['method_type']) && $params['method_type'] == 'delete') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            }

            $headers = array('Accept: application/json','User-Agent: LHC RestAPI 4.17v');

            if (isset($params['body_json']) && !empty($params['body_json'])) {

                curl_setopt($ch, CURLOPT_POST,1 );
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params['body_json']);

                if (!is_array($params['body_json'])) {
                    $headers[] = 'Content-Type: application/json';
                }

                $headers[] = 'Expect:';
            } else if (isset($params['post_params'])) {
                curl_setopt($ch, CURLOPT_POST,1 );
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params['post_params']);
            }

            if (isset($params['bearer']) && !empty($params['bearer'])) {
                $headers[] = 'Authorization: Bearer ' . $params['bearer'];
            }

            if (isset($params['headers']) && !empty($params['headers'])) {
                $headers = array_merge($headers, $params['headers']);
            }

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $startTime = date('H:i:s');
            $additionalError = ' ';

            if (isset($params['test_mode']) && $params['test_mode'] == true) {
                $content = $params['test_content'];
                $httpcode = 200;
            } else {
                $content = curl_exec($ch);

                if (curl_errno($ch))
                {
                    $additionalError = ' [ERR: '. curl_error($ch).'] ';
                }

                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            }

            $endTime = date('H:i:s');

            if (isset($params['log_response']) && $params['log_response'] == true) {
                self::$lastCallDebug['request_url_response'][] = '[T' . self::$apiTimeout . '] ['.$httpcode.']'.$additionalError.'['.$startTime . ' ... ' . $endTime.'] - ' . ((isset($params['body_json']) && !empty($params['body_json'])) ? json_encode($params['body_json']) : '') . ':' . $content;
            }

            if ($httpcode == 204) {
                return array();
            }

            if ($httpcode == 404) {
                throw new \Exception('Resource could not be found!');
            }

            if (isset($params['return_200']) && $params['return_200'] == true && $httpcode == 200) {
                return $content;
            }

            if (isset($params['expect_200'])  && $params['expect_200'] == true && $httpcode != 200) {
                throw new \Exception('Not expected HTTP code returned!');
            }

            if ($httpcode == 401) {
                throw new \Exception('No permission to access resource!');
            }

            if ($content !== false)
            {
                if (isset($params['raw_response']) && $params['raw_response'] == true){
                    return $content;
                }

                $response = json_decode($content,true);
                if ($response === null) {
                    if ($i == 2) {
                        throw new \Exception('Invalid response was returned. Expected JSON');
                    }
                } else {
                    if ($httpcode != 500) {
                        return $response;
                    }
                }

            } else {
                if ($i == 2) {
                    throw new \Exception('Invalid response was returned');
                }
            }

            if ($httpcode == 500 && $i >= 2) {
                throw new \Exception('Invalid response was returned');
            }

            usleep(300);
        }
    }


}

?>