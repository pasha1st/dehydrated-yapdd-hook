<?php
/*
Yandex API for PDD by Pasha1st
2018-04-23 v. 0.1 first release
*/
  class HTTPException extends Exception {};

  class YandexAPICore {
    protected $pddToken;
    protected $url='https://pddimp.yandex.ru';
    protected $userAgent='pfYaAPIBot/1.0';
    protected $curl;
    protected $timeout=30;

    function __construct($token,$url=null) {
      $this->pddToken=$token;
      if (!is_null($url)) $this->url=$url;
      $this->curl=curl_init($this->url);
      if (!$this->curl) throw new Exception('curl_init() failed');
    }

    public function sendQuery($path, $params, $method='GET') {

      $urlparams='';
      if (is_array($params)) {
        foreach($params as $k=>$v) {
          $urlparams.=(($urlparams==''?'':'&')).urlencode($k).'='.urlencode($v);
        };
      } else 
        $urlparams=$params;

      curl_setopt_array($this->curl, array(
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_CONNECTTIMEOUT => $this->timeout,
          CURLOPT_TIMEOUT => $this->timeout,
          CURLOPT_USERAGENT => $this->userAgent,
          CURLOPT_HTTPHEADER => array('PddToken: '.$this->pddToken),
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_POSTFIELDS=>'',

//          CURLOPT_VERBOSE => true
        )
      );

      if ($method=='GET') {
        if ($urlparams!='') $urlparams='?'.$urlparams;
        curl_setopt_array($this->curl, array(
          CURLOPT_URL => $this->url.'/'.$path.$urlparams,
          CURLOPT_HTTPGET => true,
          )
        );
      } elseif($method=='POST') {
        curl_setopt_array($this->curl, array(
          CURLOPT_URL => $this->url.'/'.$path,
          CURLOPT_POST => true,
          CURLOPT_POSTFIELDS=>$urlparams
          )
        );
//        print "Params: $urlparams\r\n";
      } else {
        curl_setopt_array($this->curl, array(
          CURLOPT_URL => $this->url.'/'.$path.$urlparams,
          CURLOPT_CUSTOMREQUEST=>$method
          )
        );
      };

      $res=curl_exec($this->curl);
      if ($res===false) throw new Exception('curl_exec() failed');

      $httpCode=curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
      if ($httpCode!=200) throw new HTTPException('HTTP request failed',$httpCode);

      return $res;
    }

    public function getDomains($page=-1,$limit=-1) {
      $params=array();
      if ($page>0) $params['page']=$page;
      if ($limit>0) $params['on_page']=$limit;

      $answer=$this->sendQuery('/api2/admin/domain/domains',$params);
      return json_decode($answer,false);
    }

  }

/*
All methods return json-decoded objects
*/
  class YandexDNS {
    protected $core;

    function __construct($token) {
      $this->core=new YandexAPICore($token);
    }

    public function getCore() {return $this->core;}

    public function getDNSRecords($domain) {
      $answer=$this->core->sendQuery('/api2/admin/dns/list',array('domain'=>$domain));
      return json_decode($answer,false);
    }

    public function addDNSRecord($domain,$record) {
//      $params=array();
      $params=$record;
      $params['domain']=$domain;

      $answer=$this->core->sendQuery('/api2/admin/dns/add',$params,'POST');
      return json_decode($answer,false);
    }

    public function editDNSRecord($domain,$record_id,$record) {
//      $params=array();
      $params=$record;
      $params['domain']=$domain;
      $params['record_id']=$record_id;

      $answer=$this->core->sendQuery('/api2/admin/dns/edit',$params,'POST');
      return json_decode($answer,false);
    }

    public function delDNSRecord($domain,$record_id) {
//      $params=array();
      $params=array();
      $params['domain']=$domain;
      $params['record_id']=$record_id;

      $answer=$this->core->sendQuery('/api2/admin/dns/del',$params,'POST');
      return json_decode($answer,false);
    }

/*
Filter records
IN: result-object from getDNSRecords()
IN: filter - array(property=>value,...)

OUT: filtered $answer->records
*/
    static public function filterDNSRecords($answer,$filter) {
      if ($answer->success!='ok') return false;
      $res=array_filter($answer->records,
        function($val) use ($filter) {
          $res=true;
          foreach($filter as $k=>$v)
            $res=$res && ($val->$k==$v);
          return $res;
        }
      );
      return $res;
    }
  }

?>