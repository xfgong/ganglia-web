<?php
// Copyright 2012 JIKE Inc. All Rights Reserved
// Author: Chris <gongxiangfeng@jike.com>

include_once dirname(__FILE__) . '/eval_conf.php';

function get_ganglia_summary(){
    global $conf;
    $ip = $conf['ganglia_ip'];
    $port = $conf['ganglia_port'];
    $timeout = 3.0;
    $errstr = '';
    $errno = '';
    $request = "/?filter=summary\n";

    print date('Y-m-d H:i:s') . " $ip:$port start\n";
    $fp = fsockopen( $ip, $port, $errno, $errstr, $timeout );
    var_dump($fp);
    if (!$fp){
        $error = "fsockopen error($errno): $errstr";
        print "ERROR: $error\n";
        return FALSE;
    }
     $rc = fputs($fp, $request);
     if (!$rc) {
        $error = "Could not sent request to gmetad: $errstr";
        print "ERROR: $error\n";
        return FALSE;
     }
     $data = array();
     while (!feof($fp)) {
         $data[] = fread($fp, 16384);
     }
     file_put_contents($conf['ganglia_summary_file'], join('', $data));

     print date('Y-m-d H:i:s') . " $ip:$port finish\n";
     return TRUE;
}

get_ganglia_summary();
