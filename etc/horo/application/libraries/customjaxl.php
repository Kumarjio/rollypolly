<?php
class Customjaxl
{
	public static function postAuth($payload, $jaxl) {
		$response = array('jaxl'=>'connected', 'jid'=>$jaxl->jid);
		$jaxl->JAXL0206('out', $response);
	}
	public static function postDisconnect($payload, $jaxl) {
		$response = array('jaxl'=>'disconnected');
		$jaxl->JAXL0206('out', $response);
	}
	public static function postEmptyBody($body, $jaxl) {
		$response = array('jaxl'=>'pinged');
		$jaxl->JAXL0206('out', $response);
	}
	
	public static function postAuthFailure($payload, $jaxl) {
		$response = array('jaxl'=>'authFailed');
		$jaxl->JAXL0206('out', $response);
	}
	
	public static function postCurlErr($payload, $jaxl) {
		if($_REQUEST['jaxl'] == 'disconnect') self::postDisconnect($payload, $jaxl);
		else $jaxl->JAXL0206('out', array('jaxl'=>'curlError', 'code'=>$payload['errno'], 'msg'=>$payload['errmsg']));
	}


	public static function create_node($payload, $jaxl)
	{
		if ($payload['type'] === 'error') {
			$response = array('jaxl'=>'error_new', 'error_message' => 'Could not create new node due to '.$payload['errorCondition'], 'id' => $jaxl->custom['id'], 'node' => $jaxl->custom['node']);
			$jaxl->JAXL0206('out', $response);
			return false;
		}

		$response = array('jaxl'=>'eventsubmitted', 'id' => $jaxl->custom['id'], 'node' => $jaxl->custom['node']);
		$jaxl->JAXL0206('out', $response);
		return true;
	}

	public static function create_node_answer($payload, $jaxl)
	{
		if ($payload['type'] === 'error') {
			$response = array('jaxl'=>'error_new', 'error_message' => 'Could not create new node due to '.$payload['errorCondition'], 'id' => $jaxl->custom['id'], 'node' => $jaxl->custom['node']);
			$jaxl->JAXL0206('out', $response);
			return false;
		}

		$response = array('jaxl'=>'eventsubmitted2', 'id' => $jaxl->custom['id'], 'node' => $jaxl->custom['node']);
		$jaxl->JAXL0206('out', $response);
		return true;
	}

	public static function create_node_presenter($payload, $jaxl)
	{
		if ($payload['type'] === 'error') {
			$response = array('jaxl'=>'error_new', 'error_message' => 'Could not create new node due to '.$payload['errorCondition'], 'id' => $jaxl->custom['id'], 'node' => $jaxl->custom['node']);
			$jaxl->JAXL0206('out', $response);
			return false;
		}

		$response = array('jaxl'=>'eventsubmitted3', 'id' => $jaxl->custom['id'], 'node' => $jaxl->custom['node']);
		$jaxl->JAXL0206('out', $response);
		return true;
	}

	public static function event_getAffiliationList($payload, $jaxl)
	{
		if ($payload['type'] === 'error') {
			$response = array('jaxl'=>'error_new', 'error_message' => 'Could not create new node due to '.$payload['errorCondition'], 'id' => $jaxl->custom['id'], 'node' => $jaxl->custom['node']);
			$jaxl->JAXL0206('out', $response);
			return false;
		}
		$response = array('jaxl'=>'set_configure', 'id' => $jaxl->custom['id'], 'node' => $jaxl->custom['node'], 'payload' => $payload, 'payloadRaw' => $jaxl->payloadRaw);
		$jaxl->JAXL0206('out', $response);
		return true;
	}
	public static function event_get_configure($payload, $jaxl)
	{

		if ($payload['type'] === 'error') {
			$response = array('jaxl'=>'error', 'error_message' => 'Could not get configuration node due to '.$payload['errorCondition'], 'id' => $jaxl->custom['id'], 'node' => $jaxl->custom['node'], 'returnval' => '', 'payload' => $payload, 'payloadRaw2' => $jaxl->payloadRaw2);
			$jaxl->JAXL0206('out', $response);
			return false;
		}

		$return = array();
		if (!empty($jaxl->payloadRaw2->pubsub->configure->x->field)) {
			$i = 0;
			foreach ($jaxl->payloadRaw2->pubsub->configure->x->field as $k => $v) {
				$return[$i]['label'] = sprintf('%s', $v['label']);
				$return[$i]['type'] = sprintf('%s', $v['type']);
				$return[$i]['var'] = sprintf('%s', $v['var']);
				$return[$i]['value'] = sprintf('%s', $v->value);
				if (!empty($v->option)) {
					$j = 0;
					foreach ($v->option as $k1 => $v1) {
						$return[$i]['option'][$j]['label'] = sprintf('%s', $v1['label']);
						$return[$i]['option'][$j]['value'] = sprintf('%s', $v1->value);
						$j++;
					}
				}
				$i++;
			}
		}

		$response = array('jaxl'=>'get_configure', 'id' => $jaxl->custom['id'], 'node' => $jaxl->custom['node'], 'payload' => $payload, 'payloadRaw2' => $jaxl->payloadRaw2, 'returnval' => $return);
		$jaxl->JAXL0206('out', $response);
		return true;
	}
	public static function event_set_configure($payload, $jaxl)
	{

		if ($payload['type'] === 'error') {
			$response = array('jaxl'=>'error', 'error_message' => 'Could not configure due to '.$payload['errorCondition'], 'id' => $jaxl->custom['id'], 'node' => $jaxl->custom['node'], 'data' => $jaxl->custom['data'], 'fields' => $jaxl->custom['fields']);
			$jaxl->JAXL0206('out', $response);
			return false;
		}

		$response = array('jaxl'=>'set_configure', 'id' => $jaxl->custom['id'], 'node' => $jaxl->custom['node'], 'payload' => $payload, 'payloadRaw2' => $jaxl->payloadRaw2, 'data' => $jaxl->custom['data'], 'fields' => $jaxl->custom['fields']);
		$jaxl->JAXL0206('out', $response);
		return true;
	}

	public static function subscribeNode($payload, $jaxl)
	{
		if ($payload['type'] === 'error') {
			$response = array('jaxl'=>'qaview', 'error_message' => 'Could not subscribe node due to '.$payload['errorCondition'], 'payload' => $payload, 'payloadRaw2' => $jaxl->payloadRaw2, 'data' => $jaxl->custom['data'], 'event_id' => $jaxl->custom['data']['event_id'], 'mode' => $jaxl->custom['data']['mode'], 'html' => $jaxl->custom['data']['html']);
			$jaxl->JAXL0206('out', $response);
			return false;
		}

		$response = array('jaxl'=>'qaview', 'error_message' => '', 'event_id' => $jaxl->custom['data']['event_id'], 'mode' => $jaxl->custom['data']['mode'], 'html' => $jaxl->custom['data']['html'], 'payload' => $payload, 'payloadRaw2' => $jaxl->payloadRaw2, 'data' => $jaxl->custom['data']);
		$jaxl->JAXL0206('out', $response);
		return true;
	}


	public static function affiliateNode($payload, $jaxl)
	{
		if ($payload['type'] === 'error') {
			$response = array('jaxl'=>'affiliate', 'error_message' => 'Could not set the affiliate to this node due to '.$payload['errorCondition'], 'payload' => $payload, 'payloadRaw2' => $jaxl->payloadRaw2, 'data' => $jaxl->custom['data'], 'custom_send_xml' => $jaxl->custom_send_xml);
			$jaxl->JAXL0206('out', $response);
			return false;
		}

		$response = array('jaxl'=>'affiliate', 'error_message' => '', 'payload' => $payload, 'payloadRaw2' => $jaxl->payloadRaw2, 'data' => $jaxl->custom['data'], 'custom_send_xml' => $jaxl->custom_send_xml);
		$jaxl->JAXL0206('out', $response);
		return true;
	}

	public static function customPublishItem($payload, $jaxl)
	{
		//pr($jaxl->custom_send_xml);
		if ($payload['type'] === 'error') {
			$response = array('jaxl'=>'qa_question_submit', 'error' => 'Could not publish due to '.$payload['errorCondition'], 'payload' => $payload, 'payloadRaw2' => $jaxl->payloadRaw2, 'data' => $jaxl->custom['data']);
			$jaxl->JAXL0206('out', $response);
			return false;
		}

		$response = array('jaxl'=>'qa_question_submit', 'error_message' => '', 'payload' => $payload, 'payloadRaw2' => $jaxl->payloadRaw2, 'data' => $jaxl->custom['data'], 'error' => '', 'qa_id' => $jaxl->custom['data']['qa_id']);
		$jaxl->JAXL0206('out', $response);
		return true;
	}
	//testing functions, do not use
	public static function thisistestfunction($payload, $jaxl)
	{
		echo 'start';
		pr($jaxl->payloadRaw);
		echo 'end';
		
		//$response = array('jaxl'=>'connected', 'payloadRaw'=>$jaxl->payloadRaw, 'payload' => $payload);
		//$jaxl->JAXL0206('out', $response);
	}

	public static function getMessage($payloads, $jaxl) {
		$response = array('jaxl'=>'message', 'payloads'=>$payloads);
		$jaxl->JAXL0206('out', $response);
		return $payloads;
		$html = '';
		foreach($payloads as $payload) {
			// reject offline message
			if($payload['offline'] != JAXL0203::$ns && $payload['type'] == 'chat') {
				if(strlen($payload['body']) > 0) {
					$html .= '<div class="mssgIn">';
					$html .= '<p class="from">'.$payload['from'].'</p>';
					$html .= '<p class="body">'.$payload['body'].'</p>';
					$html .= '</div>';
				}
				else if(isset($payload['chatState']) && in_array($payload['chatState'], JAXL0085::$chatStates)) {
					$html .= '<div class="presIn">';
					$html .= '<p class="from">'.$payload['from'].' chat state '.$payload['chatState'].'</p>';
					$html .= '</div>';
				}
			} else {
				$html .= '<div class="mssgIn">';
				$html .= '<p class="from">'.$payload['from'].'</p>';
				$html .= '<p class="body">'.$payload['body'].'</p>';
				$html .= '</div>';
			}
		}
		
		if($html != '') {
			$response = array('jaxl'=>'message', 'message'=>urlencode($html), 'type' => $payload['type']);
			$jaxl->JAXL0206('out', $response);
		}
		
		return $payloads;
	}
	public static function testx($payload, $jaxl) {
		$jaxl->requires('JAXL0060');
		$jaxl->JAXL0060('getNodeItems', 'pubsub.64.22.114.70', 'user3@64.22.114.70', 'test1223', array('Customjaxl', 'thisistestfunction'));
	}
	public static function thisistestfunction2($payload, $jaxl)
	{
		$response = array('jaxl'=>'connected', 'payloadRaw'=>$jaxl->payloadRaw, 'payload' => $payload);
		$jaxl->JAXL0206('out', $response);
	}

	public static function testy($payload, $jaxl) {
		$jaxl->requires('JAXL0060');
		$jaxl->JAXL0060('getNodeItems', 'pubsub.64.22.114.70', 'user3@64.22.114.70', 'test1223', array('Customjaxl', 'thisistestfunction2'));
	}
}
?>