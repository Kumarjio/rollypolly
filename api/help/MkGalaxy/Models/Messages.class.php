<?php
class Models_Messages extends App_base
{
  public function message_details($message_id, $uid)
  {
      $sql = sprintf("SELECT * FROM help_messages left join google_auth ON help_messages.uid = google_auth.uid WHERE help_messages.message_id = %s AND help_messages.to_uid = %s", $this->qstr($message_id), $this->qstr($uid));
      $result = $this->_connMain->Execute($sql);
      if (empty($result) || $result->EOF) throw new Exception ("No Message Found.");
      if ($result->fields['message_read'] == 0) {
          $data = array();
          $data['message_id'] = $message_id;
          $data['message_read'] = 1;
          $data['read_date'] = date('Y-m-d H:i:s');
          $where = "message_id = ".$this->qstr($message_id);
          $updateSQL = $this->_connMain->AutoExecute('help_messages', $data, 'UPDATE', $where);
      }
      return $result->fields;
  }

  public function message_view($uid, $nrows=10, $offset=0)
  {
      $sql = sprintf("SELECT * FROM help_messages left join google_auth ON help_messages.uid = google_auth.uid WHERE help_messages.to_uid = %s ORDER BY help_messages.message_date DESC", $this->qstr($uid));
      $result = $this->_connMain->CacheSelectLimit(self::CACHESECS_MESSAGES, $sql, $nrows, $offset);
      if (empty($result) || $result->EOF) throw new Exception ("No Message Found.");
      $return = array();
      while (!$result->EOF) {
          $return[] = $result->fields;
          $result->MoveNext();
       }
       return $return;
  }

    public function add($data=array())
    {
      $Models_Googleauth = new Models_Googleauth();
      $user = $Models_Googleauth->getUser($data['uid']);
      if (empty($user)) {
        throw new Exception('From User not found');
      }
      $user2 = $Models_Googleauth->getUser($data['to_uid']);
      if (empty($user2)) {
        throw new Exception('To User not found');
      }
      $insertSQL = $this->_connMain->AutoExecute('help_messages', $data, 'INSERT');
      //$message_id = $this->_connMain->Insert_ID();
      return true;
    }
}