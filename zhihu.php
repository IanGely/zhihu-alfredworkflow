<?php


require_once('workflows.php');

$wf = new Workflows();

$orig = "你好";
$opptions = array(
    CURLOPT_HTTPHEADER => array(
        'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36'
    )
);

$data = $wf->request("https://www.zhihu.com/api/v4/search_v3?t=general&q=" . urlencode($orig), $opptions);
$data = json_decode($data, true);

$paging = $data['paging'];
$results = $data['data'];

foreach ($results as $key => $item) {
  if ($item['type'] !== 'search_result') continue;
  //search result
  if ($item['object']['type'] == 'article') {
      $link = "https://zhuanlan.zhihu.com/p/" . $item['object']['id'];
  } elseif ($item['object']['type'] == 'question') {
      $link = "https://www.zhihu.com/question/" . $item['object']['id'];
  } elseif ($item['object']['type'] == 'answer') {
      $link = "https://www.zhihu.com/question/" . $item['object']['question']['id'] . '/answer/'. $item['object']['id'];
  }
  $title = strip_tags($item['highlight']['title']);
  $desc = strip_tags($item['highlight']['description']);
  $icon = 'appicons/' . $item['object']['type'] . '.png';
  $wf->result($int++ . '.' . time(), $link, $title, "$desc", $icon);
}

echo $wf->toxml();