<?php

/**
 * Send message to notify-send server.
 * 
 * @param string $summary The message summary.
 * @param string $body The message body.
 * @param string $address Remote server address.
 * 
 * @return int|string Zero on success and error message on failures.
 */
function ns($summary, $body, $level = null, $address = 'tcp://192.168.56.1:11120') {
  if ($conn = @stream_socket_client($address, $errno, $errstr)) {
    $message = array(
      'summary' => $summary,
      'body' => $body,
      'level' => $level
    );
    fwrite($conn, json_encode($message));
    fclose($conn);
    return 0;
  }
  else {
    return "$errstr ($errno)\n";
  }
}
