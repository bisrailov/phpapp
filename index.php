<?php
echo "</pre>";
echo "testing redis\n";
try {
    $redis = new Redis();
    $ret   = $redis->connect('localhost');
    if ($ret) {
        echo "redis connected\n";
        $ret = $redis->ping('test');
        echo "ping success\n";
        $redis->set('testkey', 'testvalue');
        echo "set\n";
        $ret = $redis->get('testkey');
        if ($ret == 'testvalue') {
            echo "get success\n";
        } else {
            echo "get failed\n";
        }
    }
} catch (RedisException $re) {
    echo "redis exception: " . $re->getMessage() . "\n";
}
echo "</pre>";
phpinfo();
