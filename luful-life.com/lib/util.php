<?php
function is_email($email)
{
    if (strlen($email) < 6 || !preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)) {
        echo '<script>console.log("emailエラー");</script>';
        return false;
    }
    return true;;
}
function is_tel_jp($tel)
{
    if (!preg_match('/^(0[5-9]0-[0-9]{4}-[0-9]{4}|0[1-9]-[1-9][0-9]{3}-[0-9]{4}|0[5-9]0[0-9]{8}|0[1-9][1-9][0-9]{7})$/', $tel)) {
        echo '<script>console.log("電話番号エラー");</script>';
        return false;
    } else {
        return true;
    }
}
function myTrim($str)
{
    $search = array(" ", "　", "\n", "\r", "\t");
    $replace = array("", "", "", "", "");
    return str_replace($search, $replace, $str);
}

enum Option
{
    case LP1;
    case LP2;
    public function toString(): String
    {
        return match ($this) {
            Option::LP1 => "LP1",
            Option::LP2 => "LP2",
        };
    }
}

enum Mode
{
    case DEVELOPMENT;   // テスト用
    case PRODUCTION;
}
