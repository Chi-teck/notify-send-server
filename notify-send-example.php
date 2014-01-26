<?php

foreach (array('summary', 'body', 'level', 'address') as $key) {
  $values[$key] = isset($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8') : '';  
}

if (isset($_POST)) {
  require 'notify-send-client.php';
  $error = ns($values['summary'], $values['body'], $values['level'], $values['address']);
}

?><!DOCTYPE html>
<html">
<head>
<meta charset="utf-8">
<title>Send messages to notify-send server</title>
<style>
  body {
    background: #dee5de;
    padding: 1em;
    font-size: 16px;
  }
  label {
    display: block; 
  }
  .form-element {
    width: 300px;
    margin: .5em 0;
  }
  .error {
    border: 1px solid red;
    padding: 3px;
    text-indent: 5px;
    color: red;
    background-color: #fff5f5;
  }
  input, textarea {
    width: 100%;
    border: 1px solid #777;
    padding: 3px;   
  }
</style>
</head>
<body>
  
<form role="form" method="post">
  <?php if (!empty($error)): ?>
  <div class="form-element error">
    <?php print $error; ?>
  </div>
  <?php endif; ?>  
  <div class="form-element">
    <label for="summary">Summary</label>
    <input name="summary" type="text" value="<?php print $values['summary']; ?>"/>
  </div>
  <div class="form-element">
    <label for="body">Body</label>
    <textarea name="body" rows="7"><?php print $values['body']; ?></textarea>
  </div>
  <div class="form-element">
    <label for="level">Level:</label>
    <select name="level">
      <option value="low"<?php if ($values['level'] == 'low') print ' selected'; ?>>Low</option>
      <option value="normal"<?php if ($values['level'] == 'normal') print ' selected'; ?>>Normal</option>
      <option value="critical"<?php if ($values['level'] == 'critical') print ' selected'; ?>>Critical</option>
    </select>
  </div>
  <div class="form-element">
    <label for="address">Remote address:</label>
    <input name="address" type="text" value="<?php print $values['address']; ?>"/>
  </div>
  <button type="submit">Send message</button>
</form>
  
</body>
</html>
