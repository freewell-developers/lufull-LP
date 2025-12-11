<?php

function inflow_source($utm_source, $utm_medium)
{

  $inflow_source_master = [
    "x" => "X",
    "facebook" => "Meta（Facebook）",
    "instagram" => "Meta（Instagram）",
    "link-ag" => "リンクエッジ",
  ];

  if (array_key_exists($utm_source, $inflow_source_master)) {
    return $inflow_source_master[$utm_source];
  } else if ($utm_source === 'google' && $utm_medium === 'cpc') {
    return "Googleリスティング";
  } else if (strpos($utm_source, 'referral-') !== false) {
    return "加盟者リファラル";
  } else if (strpos($utm_source, 'alliance-') !== false) {
    return "アライアンス";
  } else {
    return "オーガニック";
  }
}
