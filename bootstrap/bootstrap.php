<?php

/**
 * Direct integration with bird.com
 * */
class erLhcoreClassExtensionMbird
{
    public function __construct()
    {
        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();

        $dispatcher->listen('chat.rest_api_before_request', array(
            $this,
            'addWhatsAppToken'
        ));

        $dispatcher->listen('chat.webhook_incoming_chat_started', array(
            $this,
            'setDepartment'
        ));

        $dispatcher->listen('chat.webhook_incoming_chat_started', array(
            $this,
            'setWhatsAppToken'
        ));

        $dispatcher->listen('chat.webhook_incoming_chat_continue', array(
            $this,
            'setWhatsAppToken'
        ));

        $dispatcher->listen('chat.webhook_incoming', array(
            $this,
            'verifyRequest'
        ));
    }

    public function verifyRequest($params)
    {
        if ($params['webhook']->scope == 'birdappwhatsapp') {

            $mbOptions = erLhcoreClassModelChatConfig::fetch('mbird_options');
            $data = (array)$mbOptions->data;

            $headerSignature = $_SERVER['HTTP_MESSAGEBIRD_SIGNATURE'];
            $headerTimestamp = $_SERVER['HTTP_MESSAGEBIRD_REQUEST_TIMESTAMP'];
            $receivedBody = file_get_contents('php://input');
            $signingKey = $data['signing_key'];
            $requestedUrl = \erLhcoreClassSystem::getHost() . \erLhcoreClassDesign::baseurldirect('webhooks/incoming') . '/' . $params['webhook']->identifier;

            $receivedDecodedSignature = base64_decode($headerSignature);

            $bodyHash = hash('sha256', $receivedBody, true);
            $computedSignature = hash_hmac(
                'sha256',
                sprintf("%s\n%s\n%s", $headerTimestamp, $requestedUrl, $bodyHash),
                $signingKey,
                true
            );

            if (!hash_equals($computedSignature, $receivedDecodedSignature)) {
                throw new Exception('Request signing could not be verified');
            }
        }
    }

    public function setDepartment($params)
    {
        if (is_object($params['chat']->iwh) && $params['chat']->iwh->scope == 'birdappwhatsapp' && !empty($params['data']['payload']['channelId'])) {
            $channel = \LiveHelperChatExtension\mbird\providers\erLhcoreClassModelMBirdChannel::findOne(['filter' => ['channel_id' => $params['data']['payload']['channelId']]]);
            if (is_object($channel)) {
                $params['chat']->dep_id = $channel->dep_id;
                $params['chat']->updateThis(['update' => ['dep_id']]);
            }
        }
    }

    public function setWhatsAppToken($params)
    {
        if (is_object($params['chat']->iwh) && $params['chat']->iwh->scope == 'birdappwhatsapp') {
            $mbOptions = erLhcoreClassModelChatConfig::fetch('mbird_options');
            $data = (array)$mbOptions->data;
            $attributes = $params['webhook']->attributes;
            $attributes['access_key'] = $data['access_key'];
            $attributes['workspace_id'] = $data['workspace_id'];
            $params['webhook']->attributes = $attributes;
        }
    }

    public function addWhatsAppToken($params) {
        if (is_object($params['chat']->incoming_chat) && $params['chat']->incoming_chat->incoming->scope == 'birdappwhatsapp') {
            $mbOptions = erLhcoreClassModelChatConfig::fetch('mbird_options');
            $data = (array)$mbOptions->data;
            $attributes = $params['chat']->incoming_chat->incoming->attributes;
            $attributes['access_key'] = $data['access_key'];
            $attributes['workspace_id'] = $data['workspace_id'];
            $params['chat']->incoming_chat->incoming->attributes = $attributes;
        }
    }

    public function __get($var) {
        switch ($var) {
            case 'is_active' :
                return true;
                ;
                break;

            case 'settings' :
                $fbOptions = erLhcoreClassModelChatConfig::fetch('mbird_options');
                $data = (array)$fbOptions->data;
                $this->properties['settings'] = $data;
                return $this->properties['settings'];
            default :
                ;
                break;
        }
    }

    public static function processSent($dataSent) {
        $mbOptions = erLhcoreClassModelChatConfig::fetch('mb_options');
        $data = (array)$mbOptions->data;
        if (isset($data['fail_counter']) && $data['fail_counter'] > 0) {
            $data['fail_counter'] = 0;
            $data['fail_sent'] = 0;
            $mbOptions->value = serialize($data);
            $mbOptions->saveThis();
        }
    }

    public function run()
    {

    }

    public static function getSession() {
        if (! isset ( self::$persistentSession )) {
            self::$persistentSession = new ezcPersistentSession ( ezcDbInstance::get (), new ezcPersistentCodeManager ( './extension/mbird/pos' ) );
        }
        return self::$persistentSession;
    }
    private static $persistentSession;

    protected $properties = [];

}