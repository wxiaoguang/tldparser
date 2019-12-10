<?php
require __DIR__ . '/TldParser.php';

function main()
{
    $assertEquals = function ($a, $b, $msg = null) {
        if ($a !== $b) {
            fprintf(STDERR, "assert equals failed: a=%s, b=%s, msg: $msg\n", json_encode($a), json_encode($b));
        }
    };

    $assertEquals(tld_parse_domain('a.ck'), ['', '', 'a.ck'], "*.ck");
    $assertEquals(tld_parse_domain('b.a.ck'), ['', 'b', 'a.ck']);
    $assertEquals(tld_parse_domain('c.b.a.ck'), ['c', 'b', 'a.ck']);
    $assertEquals(tld_parse_domain('www.ck'), ['', 'www', 'ck'], "*.ck excludes www.ck");
    $assertEquals(tld_parse_domain('1.www.ck'), ['1', 'www', 'ck']);

    $assertEquals(tld_parse_domain('no_such'), ['', '', '']);
    $assertEquals(tld_parse_domain('google.com.cn'), ['', 'google', 'com.cn']);
    $assertEquals(tld_parse_domain('a.b.google.jp'), ['a.b', 'google', 'jp']);

    $assertEquals(tld_parse_domain_sld1('xxx'), '');
    $assertEquals(tld_parse_domain_sld1('b.google.com.cn'), 'b.google.com.cn');
    $assertEquals(tld_parse_domain_sld1('a.b.google.jp'), 'b.google.jp');

    echo "done";
}

main();
