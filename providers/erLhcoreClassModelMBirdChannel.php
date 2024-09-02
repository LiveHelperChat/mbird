<?php

namespace LiveHelperChatExtension\mbird\providers;

class erLhcoreClassModelMBirdChannel
{
    use \erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_mbird_channel';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionMbird::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'channel_id' => $this->channel_id,
            'dep_id' => $this->dep_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        );
    }

    public function beforeSave($params = array())
    {
        if ($this->created_at == 0) {
            $this->created_at = time();
        }

        $this->updated_at = time();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function __get($var)
    {
        switch ($var) {

            case 'updated_at_ago':
                $this->properties['updated_at_ago'] = \erLhcoreClassChat::formatSeconds(time() - $this->updated_at);
                return $this->properties['updated_at_ago'];

            case 'created_at_front':
                $this->properties['created_at_front'] = date('Ymd') == date('Ymd',$this->created_at) ? date(\erLhcoreClassModule::$dateHourFormat,$this->created_at) : date(\erLhcoreClassModule::$dateDateHourFormat,$this->created_at);
                return $this->properties['created_at_front'];

            case 'department':
                $this->properties['department'] = null;
                if ($this->dep_id > 0) {
                    try {
                        $this->properties['department'] = \erLhcoreClassModelDepartament::fetch($this->dep_id,true);
                    } catch (\Exception $e) {

                    }
                }
                return $this->properties['department'] ;

            default:
                ;
                break;
        }
    }

    private $properties = [];

    public $id = null;
    public $channel_id = '';
    public $name = '';
    public $dep_id = 0;
    public $created_at = 0;
    public $updated_at = 0;
}

?>