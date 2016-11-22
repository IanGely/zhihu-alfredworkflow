<?php

require_once('workflows.php');
$zh_url = "http://www.zhihu.com";
$wf = new Workflows();

$orig = "{query}";
$opptions = array(
    CURLOPT_HTTPHEADER => array(
        'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36'
    )
);

$data = $wf->request( "https://www.zhihu.com/autocomplete?max_matches=10&use_similar=0&token=".urlencode( $orig ) ,$opptions);

var_dump($data);
$data = json_decode( $data );
$data = $data[0];

for( $i=1; $i<count($data); $i++) {
  $item = $data[$i];


  if( $item[0] == "question" ) { 
    $link = "$zh_url/$item[0]/$item[3]";
  } else {
    $link = "$zh_url/$item[0]/$item[2]";
  }
  
  $icon = "appicons/$item[0].png";

  $wf->result( $int++.'.'.time(), $link , $item[1], "[$item[0]] $item[1]", $icon );
}

echo $wf->toxml();