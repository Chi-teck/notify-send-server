#!/usr/bin/php
<?php

/**
 * Notify send server.
 */

$options = getopt('h:p:l:');

$host = isset($options['h']) ? $options['h'] : '192.168.56.1';
$port = isset($options['p']) ? $options['p'] : '11120';
$timeout = isset($options['t']) ? $options['t'] : 12 * 3600;
$log_file = isset($options['l']) ? $options['l'] : false;


$server_address = "tcp://$host:$port";
$socket = @stream_socket_server($server_address, $errno, $errstr);
if (!$socket) {
  echo "$errstr ($errno)\n";
  notify_send('Cannot start notify-send server',  "$errstr ($errno)", 'critical');
  exit (1);
}
else {
  echo $log_entry = "Start listening $server_address \n";
  if ($log_file) {
    file_put_contents($log_file, "$log_entry\n");
  }

  notify_send('Start notify-send server script', "Listen $server_address",  'normal');

  while ($conn = stream_socket_accept($socket, $timeout, $peername)) {
    $message = stream_get_contents($conn);

    echo $log_entry = "Accepted message from $peername\n";
    if ($log_file) {
      file_put_contents($log_file, "$log_entry$message\n\n", FILE_APPEND);
    }

    $data = json_decode($message);
    notify_send($data->summary, $data->body, isset($data->level) ? $data->level : 'low');

    fwrite($conn, "Accepted\n");
    fclose($conn);
  }

  fclose($socket);
}

function notify_send($summary, $body, $level) {
  system("notify-send \"$summary\" \"$body\" -u $level\n");
}
