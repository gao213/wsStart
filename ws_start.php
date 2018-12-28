<?php
require_once './vendor/autoload.php';
use Workerman\Worker;
define("MAX_SHOW", 8192);
// 注意：这里与上个例子不同，使用的是websocket协议
$ws_worker = new Worker("websocket://0.0.0.0:2000");

// 启动4个进程对外提供服务
$ws_worker->count = 4;

// 当收到客户端发来的数据后返回hello $data给客户端
$ws_worker->onMessage = function($connection, $data)
{
if($data == "tom")
$connection->send("联接成功。输入密码。。\n");
// 当收到客户端发来的数据后返回hello $data给客户端
if($data == "4748"){
 // $connection->send("验证成功，开启log查询。。\n");
//  $connection->send($t);
$file_name      = "/log/".date("Y-m-d",time()) .".log";

$file_size      = filesize($file_name);
$file_size_new  = 0;
$add_size       = 0;
$ignore_size    = 0;
$fp = fopen($file_name, "r");
fseek($fp, $file_size);
$num = 0;
while(1){
$num++;
    clearstatcache();
    $file_size_new  = filesize($file_name);
    $add_size       = $file_size_new - $file_size;
    if($add_size > 0){
        if($add_size > MAX_SHOW){
            $ignore_size    = $add_size - MAX_SHOW;
            $add_size       = MAX_SHOW;
            fseek($fp, $file_size + $ignore_size);
        }
      //  fwrite(
        //    STDOUT,
$connection->send(fread($fp, $add_size));
      //  );  
        $file_size  = $file_size_new;
    }
    usleep(50000);
if($num>20) break;
}

fclose($fp);


}

};


// 运行worker
Worker::runAll();
