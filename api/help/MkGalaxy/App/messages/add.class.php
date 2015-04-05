<?php
//http://world.mkgalaxy.com/api/help/messages/add?data[uid]=1&data[to_uid]=2&data[message]=hello
class App_messages_add extends App_base
{
    public function execute()
    {
      $request = $_REQUEST;
      $data = $request['data'];
      if (empty($data)) {
        throw new Exception('Missing Data');
      }
      if (empty($data['uid'])) {
        throw new Exception('Missing Uid');
      }
      if (empty($data['to_uid'])) {
        throw new Exception('Missing to user id');
      }
      if (empty($data['subject'])) {
        throw new Exception('Missing subject');
      }
      if (empty($data['message'])) {
        throw new Exception('Missing message');
      }
      $message_id = guid();
      $data['message_id'] = $message_id;
      $Models_Messages = new Models_Messages();
      $Models_Messages->add($data);
      $this->return = array('confirm' => 'Message sent successfully.', 'message_id' => $message_id);
    }
}