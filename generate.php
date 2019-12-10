#!/usr/bin/evn php
<?php
function main()
{
    $lines = file_get_contents('https://publicsuffix.org/list/public_suffix_list.dat');
    $lines = explode("\n", $lines);
    $tldMap = [];
    $section = 'default';  // ICANN DOMAINS or PRIVATE DOMAINS
    foreach ($lines as $line) {
        $line = trim($line);

        if(!$line) {
            continue;
        }

        if(substr($line, 0, 2) === '//') {
            $m = null;
            if(preg_match("/===(\w+) ([\w\s]+)===/", $line, $m)) {
                if ($m[1] === 'BEGIN') {
                    $section = $m[2];
                } else if ($m[1] === 'END') {
                    $section = 'default';
                }
            }
            continue; // comment
        }

        if(substr($line, 0, 2) === '*.') {
            // wildcard
            $line = substr($line, 2);
            $tldMap[$section][$line] = 2;
            continue;
        }

        if(substr($line, 0, 1) === '!') {
            // exclude from wildcard
            $line = substr($line, 1);
            $tldMap[$section][$line] = 3;
            continue;
        }

        $tldMap[$section][$line] = 1;
    }

    $phpMap = var_export($tldMap, true);
    $phpMap = str_replace('array (', '[', $phpMap);
    $phpMap = str_replace(')', ']', $phpMap);
    $phpMap = preg_replace("/=>\s+/m", '=> ', $phpMap);
    $phpMap = preg_replace("/\[\s+]/m", '[]', $phpMap);

    $now = date('Y-m-d H:i:s');

    if (file_exists(__DIR__ . '/TldParser.php')) {
        echo "generates php code\n";
        file_put_contents(__DIR__ . '/TldList.data.php', "<?php
// generated on '$now'
return $phpMap;
");
    }

    if (file_exists(__DIR__ . '/tld_parser.go')) {
        echo "generates go code\n";
        $goMap = str_replace('=>', ':', $phpMap);
        $goMap = str_replace("'", '"', $goMap);
        $goMap = trim($goMap, "[] \t\r\n");
        $goMap = str_replace(']', '}', $goMap);
        $goMap = str_replace('[', 'map[string]int {', $goMap);
        file_put_contents(__DIR__ . '/tld_list_data.go', "package tldparser
// @formatter:off
// generated on '$now'
var tldMap = map[string]map[string]int {
$goMap
}
");
    }
}

main();
