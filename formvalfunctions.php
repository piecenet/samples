<?php

// Functions for validating form data
// All functions expect "Password" as field name in order to be exempted from functions

function reqfields($fields, $nopassword=false) {
  // Test for required fields
  global $errormessage; // global error message is used to append these errors
  foreach ($fields as $value) {
    if ($value == "Password" && $nopassword=true) {
      // Just skip if exempting password (default)
    } else {
      if ($_POST["$value"] == "") {
        $errormessage .= "$value is required.<br />";
      }
    }
  }
}

function uniqueid($table, $field, $testdata) {
  // Test for unique ID
  if (!get_magic_quotes_gpc()){ $testdata = addslashes($testdata); }
  $sql = "SELECT `$field` FROM `$table` WHERE `$field`='$testdata'";
  if ($result = mysql_query($sql)) {
    if (mysql_numrows($result) > 0) {
      return false;
    }
  } else {
    fataldberror("Error reading $table table to verify $field!");
  }
  return true;
}

function createsqlinsert($table, $fields, $nopassword=false) {
  // Creates SQL insert statement for specified fields in a specified table
  $fieldnames = "";
  $datafields = "";
  foreach ($fields as $value) {
    global ${$value}; // global field names are used to retrieve field values
    if ($value == "Password" && $nopassword == true) {
      // Just skip if exempting password (default)
    } else {
      $fieldnames .= "`$value`, ";
      if ($value == "Password") { $datafields .= "sha1('${$value}'), "; }
      else {
        if (function_exists("mysql_real_escape_string")) {
          // Since this could be security issue, we create a fatal error if not found
          // This vital function prevents SQL injection attacks
          $thisstring = htmlspecialchars_decode(${$value}); // prevents doubling of special characters!
          $thisstring = stripslashes($thisstring); // prevents doubling of slashes!
          $datafields .= "'".mysql_real_escape_string($thisstring)."', ";
        } else { fataldberror("Can't really escape - the function doesn't exist!"); }
      }
    }
  }
  $fieldnames = trim(trim($fieldnames), ',');
  $datafields = trim(trim($datafields), ',');
  $tableparts = explode(".", $table);
  if ($tableparts[1]) { $sql = "INSERT INTO `" . $tableparts[0] . "`.`". $tableparts[1] . "` ($fieldnames) VALUES ($datafields)"; }
  else { $sql = "INSERT INTO `$table` ($fieldnames) VALUES ($datafields)"; }
  return $sql;
}

function createsqlinsertupdate($table, $fields, $keys='', $keyvalues='', $nopassword=false) {
  // Creates SQL insert statement with update on duplicate for specified fields in a specified table
  $fieldnames = "";
  $datafields = "";
  $fielddata = "";
  foreach ($fields as $value) {
    global ${$value}; // global field names are used to retrieve field values
    if ($value == "Password" && $nopassword == true) {
      // Just skip if exempting password (default)
    } else {
      $fieldnames .= "`$value`, ";
      $fielddata .= "`$value`=";
      if ($value == "Password") {  $datafields .= "sha1('${$value}'), "; $fielddata .= "sha1('${$value}'), "; }
      else {
        if (function_exists("mysql_real_escape_string")) {
          // Since this could be security issue, we create a fatal error if not found
          // This vital function prevents SQL injection attacks
          $thisstring = htmlspecialchars_decode(${$value}); // prevents doubling of special characters!
          $thisstring = stripslashes($thisstring); // prevents doubling of slashes!
          $datafields .= "'".mysql_real_escape_string($thisstring)."', ";
          $fielddata .= "'".mysql_real_escape_string($thisstring)."', ";
        } else { fataldberror("Can't really escape - the function doesn't exist!"); }
      }
    }
  }
  if ($keys != "") {
    $keyarray = explode("|", $keys);
    $keyvaluesarray = explode("|", $keyvalues);
    foreach ($keyarray as $thiskey) {
      $fieldnames .="`$thiskey`, "; // add key in!
    }
    foreach ($keyvaluesarray as $thiskeyarray) {
      $datafields .= "'$thiskeyarray', "; // add key value in!
    }
  }
  $fieldnames = trim(trim($fieldnames), ',');
  $datafields = trim(trim($datafields), ',');
  $fielddata = trim(trim($fielddata), ',');
  $tableparts = explode(".", $table);
  if ($tableparts[1]) {
    $sql = "INSERT INTO `" . $tableparts[0] . "`.`". $tableparts[1] . "` ($fieldnames) VALUES ($datafields)";
  }
  else { $sql = "INSERT INTO `$table` ($fieldnames) VALUES ($datafields)"; }
  $sql .= " ON DUPLICATE KEY UPDATE $fielddata";
  return $sql;
}

function createsqlupdate($table, $fields, $where, $nopassword=false) {
  // Creates SQL update statement for specified fields in a specified table
  $fielddata = "";
  foreach ($fields as $value) {
    global ${$value}; // global field names are used to retrieve field values
    if ($value == "Password" && $nopassword == true) {
      // Just skip if exempting password (default)
    } else {
      $fielddata .= "`$value`=";
      if ($value == "Password") { $fielddata .= "sha1('${$value}'), "; }
      else {
        if (function_exists("mysql_real_escape_string")) {
          // Since this could be security issue, we create a fatal error if not found
          // This vital function prevents SQL injection attacks
          $thisstring = htmlspecialchars_decode(${$value}); // prevents doubling of special characters!
          $thisstring = stripslashes($thisstring); // prevents doubling of slashes!
          $fielddata .= "'".mysql_real_escape_string($thisstring)."', ";
        } else { fataldberror("Can't really escape - the function doesn't exist!"); }
      }
    }
  }
  $fielddata = trim(trim($fielddata), ',');
  $tableparts = explode(".", $table);
  if ($tableparts[1]) { $sql = "UPDATE `" . $tableparts[0] . "`.`". $tableparts[1] . "` SET $fielddata WHERE $where"; }
  else { $sql = "UPDATE `$table` SET $fielddata WHERE $where"; }
  return $sql;
}

function createsqlselect($table, $fields, $where='na', $order='na') {
  // Creates SQL select statement for specified fields in a specified table
  $fieldnames = "";
  foreach ($fields as $value) {
    // Protects password from being selected (handled elsewhere when needed)
    if ($fields != "Password") { $fieldnames .= " `$value`".", "; }
  }
  $fieldnames = trim(trim($fieldnames), ',');
  $tableparts = explode(".", $table);
  if ($tableparts[1]) { $sql = "SELECT $fieldnames FROM `" . $tableparts[0] . "`.`". $tableparts[1] . "`"; }
  else { $sql = "SELECT $fieldnames FROM `$table`"; }
  if ($where != 'na') { $sql .= " WHERE $where"; }
  if ($order != 'na') { $sql .= " ORDER BY $order"; }
  return $sql;
}

function datecorrection ($date) {
  // Converts standard date format to MySQL date format
  $pattern = '/[.\-\/]/'; // possible dividers
  $datearray = preg_split ($pattern, $date);
  foreach ($datearray as $datepart) { // first assume a 4 digit year
    if (strlen($datepart) == 4) { $year = $datepart; }
      else {
        if (!$month) { $month = $datepart; } else { $day = $datepart; }
      }
  }
  if (!$year) { // if no year, try archaic format
    $month = $datearray[0];
    $day   = $datearray[1];
    $year  = $datearray[2];
    $year = "20" . $year;
  }
  if (strlen($month) == 1) { $month = "0" . $month; } // pad month
  if (strlen($day) == 1) { $day = "0" . $day; } // pad day
  $correctdate = "$year-$month-$day"; // final corrected date
  return $correctdate;
}

?>