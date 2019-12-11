<?php

function tld_parse_domain($domain)
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
    for ($i = strlen($domain) - 1; $i >= -1; $i--) {
        if ($i == -1 || $domain[$i] === '.') {
            $s2 = substr($domain, $i+1);
            if (isset($tldMap[$s2]) || $type === 2 /* wildcard */) {
                $type = $tldMap[$s2] ?? 1;
                if ($type === 3) {
                    // exclude from wildcard
                    break;
                }

                $s1 = substr($domain, 0, max($i, 0));
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

function tld_parse_domain_fld_sld($domain)
{
    if (is_string($domain)) $domain = tld_parse_domain($domain);
    list($sub, $main, $tld) = $domain;

    $fld = '';
    $sld1 = '';
    $sld2 = '';
    if ($main !== '') {
        $fld = "$main.$tld";
        if ($sub !== '') {
            $p = strrpos($sub, '.');
            $s = ($p === false) ? $sub : substr($sub, $p+1);
            $sld1 = "$s.$main.$tld";

            if ($p !== false) {
                $p = strrpos(substr($sub, 0, $p), '.');
                $s = ($p === false) ? $sub : substr($sub, $p+1);
                $sld2 = "$s.$main.$tld";
            }
        }
    }
    return [$fld, $sld1, $sld2];
}
