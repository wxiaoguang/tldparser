<?php

function tld_parse_domain($s)
{
    static $tldMapAll = null;
    if(!$tldMapAll) {
        $tldMapAll = require __DIR__ . '/TldList.data.php';
    }
    $sub = '';
    $main = '';
    $tld = '';
    $tldMap = $tldMapAll['ICANN DOMAINS'];
    $type = null;
    for ($i = strlen($s) - 1; $i >= -1; $i--) {
        if ($i == -1 || $s[$i] === '.') {
            $s2 = substr($s, $i+1);
            if (isset($tldMap[$s2]) || $type === 2 /* wildcard */) {
                $type = $tldMap[$s2] ?? 1;
                if ($type === 3) {
                    // exclude from wildcard
                    break;
                }

                $s1 = substr($s, 0, max($i, 0));
                $tld = $s2;
                $p = strrpos($s1, '.');
                if($p !== false) {
                    $sub = substr($s1, 0, $p);
                    $main = substr($s1, $p+1);
                } else {
                    $sub = '';
                    $main = $s1;
                }
            } else {
                break;
            }
        }
    }

    return [$sub, $main, $tld];
}

function tld_parse_domain_sld1($s)
{
    list($sub, $main, $tld) = tld_parse_domain($s);
    if ($sub !== '') {
        $p = strrpos($sub, '.');
        $s = ($p === false) ? $sub : substr($sub, $p+1);
        return "$s.$main.$tld";
    }
    return "";
}
