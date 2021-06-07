<?php
echo "<pre>";
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
echo "<pre>";
echo "testing zookeeper1\n";
try {
    $connect_wait_step_size = 2000;
    $connect_wait_max = 10000;
    $connection_timeout = 5;
    //connect
    $zk_h = new Zookeeper();
    $zk_h->connect('172.20.28.214:2181');
    $deadline = microtime(true) + $connection_timeout;
    $attempt  = 1;
    while ($zk_h->getState() != Zookeeper::CONNECTED_STATE) {
        if ($deadline <= microtime(true)) {
            throw new RuntimeException("Zookeeper connection timed out!");
        }
        usleep(min($attempt++ * $connect_wait_step_size, $connect_wait_max));
    }
    //write
    $lock_key = $zk_h->create(
        '/test', // path
        123, // value
        array(
            "perms"  => Zookeeper::PERM_ALL,
            "scheme" => "world",
            "id"     => "anyone",
        ), // ACL
        Zookeeper::EPHEMERAL // flags
    );
    if (!$lock_key) {
        throw new RuntimeException("Failed creating node");
    }
    //read
    $val = $zk_h->get('/test');
    if($val != 123){
        throw new Exception('unexpected value, got' . $val);
    }
    //cleanup
    $zk_h->delete('/test');
}catch(Exception $e){
    echo "Exception: " . $e->getMessage() . "\n";
}
echo "</pre>";
phpinfo();
