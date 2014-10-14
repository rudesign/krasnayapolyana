<?php
class Messages extends Db{
    
    public static function set(){
        self::$table = 'messages';

        return new Query(self::$table);
    }

    public static function get(&$query = null, $options = array()){
        try{
            if(empty($query)) if(!$query = self::set()) throw new Error();

            return parent::get($query, $options);
        }catch (Error $e){
            return false;
        }
    }

    public static function getById($id = 0, $key = ''){
        try{
            if(!self::set()) throw new Error();

            return parent::getById($id, $key);
        }catch (Error $e){
            return false;
        }
    }

    public static function create($data = array()){
        try{
            $query = self::set();

            $query->values = array_merge(array(
                'createdTime' => time(),
            ), $data);

            if(!$query->values['createdBy']) $query->values['createdBy'] = Users::$current['id'];

            if(!$id = $query->write()) throw new Error();

            return $id;
        }catch (Error $e){
            return false;
        }
    }

    public static function send($addresseeId = 0, $body = '', $senderId = 1){
        try{
            if(empty($addresseeId)) throw new Error();
            if(empty($body)) throw new Error();
            if(empty($senderId)) throw new Error();

            $data = array(
                'addressee' => $addresseeId,
                'body' => $body,
                'createdBy' => $senderId,
            );

            if(!self::create($data)) throw new Error();

            // skip if existed (because of unique key)
            Contacts::create(array('addressee'=>$addresseeId, 'sender'=>$senderId));

            // update modify time if existed
            if(!Contacts::create(array('sender'=>$addresseeId, 'addressee'=>$senderId))){
                $query = Contacts::set();

                $query->fields = 'modifiedTime';
                $query->values = time();
                //$query->condition = '((addressee = '.Core::$item['id'].' AND sender = '.Users::$current['id'].') OR (sender = '.Core::$item['id'].' AND addressee = '.Users::$current['id'].'))';
                $query->condition = '((addressee = '.$addresseeId.' AND sender = '.$senderId.') OR (sender = '.$addresseeId.' AND addressee = '.$senderId.'))';

                $query->update();
            }

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public static function getUnreadCount($addresseeId = 0, $senderId = 0){
        try{
            if(empty($addresseeId)) throw new Error();

            $query = Contacts::set();

            $query->join = 'messages';
            $query->compare = 'messages.addressee = contacts.addressee';
            $query->fields = $query->tables[0].'.id AS ids';
            $query->condition = "messages.addressee = ".$addresseeId;
            if(!empty($senderId)) $query->condition .= " AND messages.createdBy = ".$senderId;
            $query->condition .= " AND messages.shown = 0  AND contacts.blocked = 0";
            $query->group .= $query->tables[0].'.id';
            $query->visibleOnly = false;

            if(!$query->get()) throw new Error();

            return count($query->result);
        }catch (Error $e){
            return 0;
        }
    }
}
?>