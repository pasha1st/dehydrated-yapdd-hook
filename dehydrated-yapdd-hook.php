#!/usr/bin/env php
<?php
/*
Hook для dehydrated для использования с Яндекс.Почтой для домена
Токены берутся из pdd-config.txt
Дополнительно может рестартовать апач (или пропишите свой обработчик)
*/
require('pdd-api.php');

$configname=__DIR__.'/pdd-config.txt';
$restartApache=false;

if (!file_exists($configname)) exit;

$tokens=array('default'=>'');
foreach(file($configname) as $ln) {
  $ln=trim($ln);
  if ($ln=='') continue;
  if ($ln{0}=='#') continue;
  $ar=explode('=',$ln);
  if (count($ar)==1) {
    $tokens['default']=$ln;
  } else {
    $tokens[$ar[0]]=$ar[1];
  };
};

@$cmd=$argv[1];

switch($cmd) {
  case 'deploy_challenge':
    @$domain=$argv[2];
    @$key=$argv[4];
    $token=getDomainToken($domain,$token_domain);

    $api=new YandexDNS($token);
    $api->addDNSRecord($token_domain,array(
      'type'=>'TXT',
      'content'=>$key,
      'subdomain'=>"_acme-challenge.$domain.",
    ));
    break;
  case 'clean_challenge':
    @$domain=$argv[2];
    @$key=$argv[4];
    $token=getDomainToken($domain,$token_domain,$prefix);

    $api=new YandexDNS($token);
    $answer=$api->getDNSRecords($token_domain);

    if ($answer->success=='ok') {
      $records=$api->filterDNSRecords($answer,array('type'=>'TXT','subdomain'=>'_acme-challenge'.(($prefix=='')?'':'.'.$prefix),'content'=>$key));
      if ($records) {
        $record=array_shift($records);
        $api->delDNSRecord($token_domain,$record->record_id);
      };
    };

    break;
  case 'deploy_cert':
    if ($restartApache)
      system('apachectl -k restart');
    break;
  default:;
};

function getDomainToken($domain,&$token_domain='',&$prefix='') {
global $tokens;
  $prefix='';
  $token_domain=$domain;

  if (array_key_exists($domain,$tokens)) {
    return $tokens[$domain];
  };

  foreach($tokens as $k=>$v) {
    if (strcasecmp($k,substr($domain,-strlen($k)))==0) {
      if ((strlen($k)!=strlen($domain))&&(substr($domain,-strlen($k)-1,1)!='.')) continue;
      $token_domain=$k;
      $prefix=substr($domain,0,strlen($domain)-strlen($k)-1);
      return $v;
    };
  };

  return $tokens['default'];
}

?>