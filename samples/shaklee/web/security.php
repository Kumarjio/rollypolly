<?php

define ('HMAC_SHA256', 'sha256');
define ('SECRET_KEY', '325021d2d0b44d438ce62009736e38e4f8cb8bf73b3e42e2b9256042362f5f6429789b1d8daa494cb44d772772585e4f87dff6959f9c4cb59738771877c7c7e13641027c6bfa43aca89801af19fd0cc6a4b6777b25fd4854a274a5f84b0142e59247ab31f0f3486c81ef5c5385857ab6466b0784366d49a69a40359a6c157525');

function sign ($params) {
  return signData(buildDataToSign($params), SECRET_KEY);
}

function signData($data, $secretKey) {
    return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
}

function buildDataToSign($params) {
        $signedFieldNames = explode(",",$params["signed_field_names"]);
        foreach ($signedFieldNames as $field) {
           $dataToSign[] = $field . "=" . $params[$field];
        }
        return commaSeparate($dataToSign);
}

function commaSeparate ($dataToSign) {
    return implode(",",$dataToSign);
}

?>
