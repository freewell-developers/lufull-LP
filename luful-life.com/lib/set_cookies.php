<?php
session_start();

/**
 * クエリパラメータが存在すればクッキーをセットする関数
 */
function setCookieIfExists($name, $value, $expiry, $path, $domain)
{
    if (isset($value)) {
        if ($value === '') {
            setcookie($name, '', time() - 3600, $path, $domain);
        } else {
            // ローカル環境ではドメイン無しでセット（空文字だとドメイン指定なしになる）
            if ($domain === '') {
                setcookie($name, $value, $expiry, $path);
            } else {
                setcookie($name, $value, $expiry, $path, $domain);
            }
        }
    }
}

// -------------------------------
// 環境によってドメインを自動切り替え
// -------------------------------
$host = $_SERVER['HTTP_HOST'];
$domain = (str_contains($host, 'localhost') || str_contains($host, '127.0.0.1')) ? '' : '.luful-life.com';

// -------------------------------
// クッキーの基本設定
// -------------------------------
$expiry = time() + 60 * 60 * 24 * 7;
$path = "/";

// -------------------------------
// クッキーを設定（必要ならセッションにも保存しておく）
// -------------------------------
setCookieIfExists("_itf_utm_source", $_GET["utm_source"] ?? null, $expiry, $path, $domain);
setCookieIfExists("_itf_utm_medium", $_GET["utm_medium"] ?? null, $expiry, $path, $domain);
setCookieIfExists("_itf_utm_campaign", $_GET["utm_campaign"] ?? null, $expiry, $path, $domain);
setCookieIfExists("_itf_utm_content", $_GET["utm_content"] ?? null, $expiry, $path, $domain);
setCookieIfExists("_itf_aid", $_GET["aid"] ?? null, $expiry, $path, $domain);
setCookieIfExists("_from_dm", $_GET["from_dm"] ?? null, $expiry, $path, $domain);
