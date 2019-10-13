<?php

$serverIp = $argv[1];
$serverName = $argv[2];
$serverPassword = $argv[3];
$servantName = $argv[4];
$fileName = $argv[5];
$serverId = $argv[6];

$servantNameArr = explode('.', $servantName);
$time = time();

$post = [
    'application' => $servantNameArr[0],
    'module_name' => $servantNameArr[1],
    'comment' => 'travis build ' . $fileName,
    'task_id' => $time . '000',
    'suse' => curl_file_create($fileName),
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://$serverIp/pages/server/api/upload_patch_package");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_USERPWD, "$serverName:$serverPassword");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$ret = curl_exec($ch);
if (curl_errno($ch) != 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
    curl_close($ch);
    echo "upload package failed -1\n";
    exit(-1);
}
curl_close($ch);
$ret = json_decode($ret, true);
if ($ret == false || $ret['ret_code'] != 200) {
    echo "upload package failed -2\n";
    exit(-2);
}

$id = $ret['data']['id'];

echo "finish update package id is $id \n";

$post = '{"serial":true,"items":[{"server_id":"' . $serverId . '","command":"patch_tars","parameters":{"patch_id":"' . $id . '","bak_flag":false,"update_text":""}}]}';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://$serverIp/pages/server/api/add_task");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_USERPWD, "$serverName:$serverPassword");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
$ret = curl_exec($ch);
if (curl_errno($ch) != 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
    curl_close($ch);
    echo "deploy failed -1\n";
    exit(-1);
}
curl_close($ch);
$ret = json_decode($ret, true);
if ($ret == false || $ret['ret_code'] != 200) {
    echo "deploy failed -2\n";
    exit(-2);
}

echo "deploy success data is {$ret['data']} \n";