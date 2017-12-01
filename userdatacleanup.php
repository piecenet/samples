<?php

/**********************************************************************
 * User Data Validation and Cleanup                                   *
 * Version 2009.0000                                                  *
 * (C)2009 A Piece of the Net                                         *
 **********************************************************************/

// Note this included section expects to see the following user variables:
// $FirstName, $MiddleName, $LastName, $SuffixName, $PreferredName, $Address, $City, $ZipCode
// Make sure the variables have already been assigned and that the variables refer to the user data!
// Also note that the site function cleaninput should have already stripped excess spacing.

// Store FirstName with Proper Case
if ($FirstName) {
  $casexfindarray  = array(" And ", " Or "); // define exceptions here
  $casexplacearray = array(" and ", " or "); // define exceptions here
  $FirstName = strtolower($FirstName); // first lowercase string
  $names_array = explode('-', $FirstName); // split hyphenated names
  for ($i = 0; $i < count($names_array); $i++) {
    $names_array[$i] = ucfirst($names_array[$i]); // uppercase the first letter
  }
  $FirstName = implode('-', $names_array); // put hyphenated names back together
  if (preg_match("/[\'\"\()]/", $FirstName)) {
    // fix names in ' ', " ", and ( )
    $names_array = explode(' ', $FirstName); // split words in name
    for ($i = 0; $i < count($names_array); $i++) {
      if (ereg('^\'',$names_array[$i]) || ereg('^\"',$names_array[$i]) || ereg('^\(',$names_array[$i])) {
        $names_array[$i][1] = strtoupper($names_array[$i][1]);
      }
    }
    $FirstName = implode(' ', $names_array); // put words in name back together
  }
  $FirstName = ucwords($FirstName); // uppercase each first letter
  $FirstName = str_replace($casexfindarray, $casexplacearray, $FirstName); // correct exceptions
}

// Store MiddleName with Proper Case
if ($MiddleName) {
  // Note that some people use last names here (as in maiden names).
  $casexfindarray  = array("De ", "Del ", "Der ", "La ", "Las ", "Le ", "Van ", "Vit ", "Von "); // define exceptions here
  $casexplacearray = array("de ", "del ", "der ", "la ", "las ", "le ", "van ", "vit ", "von "); // define exceptions here
  // Note: Special exceptions not found in "casex": Mc?, Mac?, D'? and O'?
  $MiddleName = strtolower($MiddleName); // first lowercase string
  $names_array = explode('-', $MiddleName); // split hyphenated names
  for ($i = 0; $i < count($names_array); $i++) {
    if (strncmp($names_array[$i],'mc',2) == 0 || ereg('^[oO]\'[a-zA-Z]',$names_array[$i]) || ereg('^[dD]\'[a-zA-Z]',$names_array[$i])) {
      // fix Mc?, O'?, and D'?
      $names_array[$i][2] = strtoupper($names_array[$i][2]);
    }
    if (strncmp($names_array[$i],'mac',3) == 0 && strncmp($names_array[$i],'mack',4) != 0) {
      // fix Mac?, except for MacK
      $names_array[$i][3] = strtoupper($names_array[$i][3]);
    }
    $names_array[$i] = ucfirst($names_array[$i]); // uppercase the first letter
  }
  $MiddleName = implode('-', $names_array); // put hyphenated names back together
  if (preg_match("/[\'\"\()]/", $MiddleName)) {
    // fix names in ' ', " ", and ( )
    $names_array = explode(' ', $MiddleName); // split words in name
    for ($i = 0; $i < count($names_array); $i++) {
      if (ereg('^\'',$names_array[$i]) || ereg('^\"',$names_array[$i]) || ereg('^\(',$names_array[$i])) {
        $names_array[$i][1] = strtoupper($names_array[$i][1]);
      }
    }
    $MiddleName = implode(' ', $names_array); // put words in name back together
  }
  $MiddleName = ucwords($MiddleName); // uppercase each first letter
  $MiddleName = str_replace($casexfindarray, $casexplacearray, $MiddleName); // correct exceptions
}

// Store LastName with Proper Case
if ($LastName) {
  $casexfindarray  = array("De ", "Del ", "Der ", "La ", "Las ", "Le ", "Van ", "Vit ", "Von "); // define exceptions here
  $casexplacearray = array("de ", "del ", "der ", "la ", "las ", "le ", "van ", "vit ", "von "); // define exceptions here
  // Note: Special exceptions not found in "casex": Mc?, Mac?, D'? and O'?
  $LastName = strtolower($LastName); // first lowercase string
  $names_array = explode('-', $LastName); // split hyphenated names
  for ($i = 0; $i < count($names_array); $i++) {
    if (strncmp($names_array[$i],'mc',2) == 0 || ereg('^[oO]\'[a-zA-Z]',$names_array[$i]) || ereg('^[dD]\'[a-zA-Z]',$names_array[$i])) {
      // fix Mc?, O'?, and D'?
      $names_array[$i][2] = strtoupper($names_array[$i][2]);
    }
    if (strncmp($names_array[$i],'mac',3) == 0 && strncmp($names_array[$i],'mack',4) != 0) {
      // fix Mac?, except for MacK
      $names_array[$i][3] = strtoupper($names_array[$i][3]);
    }
    $names_array[$i] = ucfirst($names_array[$i]); // uppercase the first letter
  }
  $LastName = implode('-', $names_array); // put hyphenated names back together
  if (preg_match("/[\'\"\()]/", $LastName)) {
    // fix names in ' ', " ", and ( )
    $names_array = explode(' ', $LastName); // split words in name
    for ($i = 0; $i < count($names_array); $i++) {
      if (ereg('^\'',$names_array[$i]) || ereg('^\"',$names_array[$i]) || ereg('^\(',$names_array[$i])) {
        $names_array[$i][1] = strtoupper($names_array[$i][1]);
      }
    }
    $LastName = implode(' ', $names_array); // put words in name back together
  }
  $LastName = ucwords($LastName); // uppercase each first letter
  $LastName = str_replace($casexfindarray, $casexplacearray, $LastName); // correct exceptions
}

// Ensure SuffixName Uses Standard Abbreviations & Case
if ($SuffixName) {
  $casexfindarray  = array("1", "Ii", "Iii", "Iv", "Ll", "Lll", "Dd", "Do", "Md", "Phd", "Mister", "Mistress", "Miss"); // define exceptions here
  $casexplacearray = array("I", "II", "III", "IV", "II", "III", "DD", "DO", "MD", "PhD", "Mr"    , "Mrs"     , "Ms"  ); // define exceptions here
  $SuffixName = ucwords(strtolower($SuffixName));
  $SuffixName = str_replace(".", "", $SuffixName); // remove periods
  $SuffixName = str_replace($casexfindarray, $casexplacearray, $SuffixName); // correct exceptions
}

// Store PreferredName with Proper Case
if (!$PreferredName) { $PreferredName = $FirstName; }
if ($PreferredName) {
  // Note that people sometimes use first and last name here as well as titles, etc.
  $casexfindarray  = array(" And ", " Or ", "De ", "Del ", "Der ", "La ", "Las ", "Le ", "Van ", "Vit ", "Von "); // define exceptions here
  $casexplacearray = array(" and ", " or ", "de ", "del ", "der ", "la ", "las ", "le ", "van ", "vit ", "von "); // define exceptions here
  // Note: Special exceptions not found in "casex": Mc?, Mac?, D'? and O'?
  $PreferredName = strtolower($PreferredName); // first lowercase string
  $names_array = explode('-', $PreferredName); // split hyphenated names
  for ($i = 0; $i < count($names_array); $i++) {
    if (strncmp($names_array[$i],'mc',2) == 0 || ereg('^[oO]\'[a-zA-Z]',$names_array[$i]) || ereg('^[dD]\'[a-zA-Z]',$names_array[$i])) {
      // fix Mc?, O'?, and D'?
      $names_array[$i][2] = strtoupper($names_array[$i][2]);
    }
    if (strncmp($names_array[$i],'mac',3) == 0 && strncmp($names_array[$i],'mack',4) != 0) {
      // fix Mac?, except for MacK
      $names_array[$i][3] = strtoupper($names_array[$i][3]);
    }
    $names_array[$i] = ucfirst($names_array[$i]); // uppercase the first letter
  }
  $PreferredName = implode('-', $names_array); // put hyphenated names back together
  if (preg_match("/[\'\"\()]/", $PreferredName)) {
    // fix names in ' ', " ", and ( )
    $names_array = explode(' ', $PreferredName); // split words in name
    for ($i = 0; $i < count($names_array); $i++) {
      if (ereg('^\'',$names_array[$i]) || ereg('^\"',$names_array[$i]) || ereg('^\(',$names_array[$i])) {
        $names_array[$i][1] = strtoupper($names_array[$i][1]);
      }
    }
    $PreferredName = implode(' ', $names_array); // put words in name back together
  }
  $PreferredName = ucwords($PreferredName); // uppercase each first letter
  $PreferredName = str_replace($casexfindarray, $casexplacearray, $PreferredName); // correct exceptions
}

// Store Address with Abbreviations and No Periods
if ($Address) {
  $casexfindarray  = array("Cr ", "Fm ", "Ih ", "Ne ", "Nw ", "Po ", "Rr ", "Se ", "Sw "); // define exceptions here
  $casexplacearray = array("CR ", "FM ", "IH ", "NE ", "NW ", "PO ", "RR ", "SE ", "SW "); // define exceptions here
  $directionalarray = array("N","NORTH","S","SOUTH","E","EAST","W","WEST","NE","NORTHEAST","NW","NORTHWEST","SE","SOUTHEAST","SW","SOUTHWEST");
  // $streettypearray is same as in pcpfneighbors.php - probably should be an include instead! (but we also need non-abbreviations here...)
  $streettypearray = array("ALY","ARC","AVE","BAYOO","BYU","BEACH","BCH","BEND","BND","BLUFF","BLF","BLUFFS","BLFS");
  array_push($streettypearray,"BLVD","BRANCH","BRIDGE","BR","BRG","BROOK","BRK","BROOKS","BRKS","BURG","BG","BGS","BYPASS","BYP");
  array_push($streettypearray,"CAMP","CP","CANYON","CYN","CAPE","CPE","CSWY","CENTER","CTR","CTRS","CHSE","CIRCLE","CIR","CIRS");
  array_push($streettypearray,"CREEK","CRK","CLIFF","CLF","CLIFFS","CLFS","CLUB","CLB","CMN","CMNS","CORNER","COR","CORS");
  array_push($streettypearray,"CRSE","COURT","CT","COURTS","CTS","COVE","CV","CVS","CREST","CRES","CRST","CROSSING","XING","XRD","XRDS");
  array_push($streettypearray,"CURVE","CURV","DALE","DL","DAM","DM","DIVIDE","DV","DR","DRS","ESTATE","EST","ESTS","EXPY","EXTS");
  array_push($streettypearray,"FALL","FALLS","FLS","FERRY","FRY","FIELD","FLD","FIELDS","FLDS","FLAT","FLT","FLATS","FLTS");
  array_push($streettypearray,"FORD","FRD","FORDS","FRDS","FOREST","FRST","FORGE","FRG","FRGS","FORK","FRK","FRKS","FORT","FT","FWY");
  array_push($streettypearray,"GARDEN","GDN","GARDENS","GDNS","GATEWAY","GTWY","GLEN","GLN","GLNS","GREEN","GRN","GREENS","GRNS");
  array_push($streettypearray,"GROVE","GRV","GROVES","GRVS","HARBOR","HBR","HARBORS","HBRS","HAVEN","HVN","HEIGHTS","HTS","HWY");
  array_push($streettypearray,"HILL","HL","HILLS","HLS","HOLLOW","HOLW","INLET","INLT","ISLAND","IS","ISS","ISLE");
  array_push($streettypearray,"JUNCTION","JCT","JCTS","KEY","KY","KEYS","KYS","KNOLL","KNL","KNOLLS","KNLS");
  array_push($streettypearray,"LAKE","LK","LAKES","LKS","LAND","LNDG","LANE","LN","LIGHT","LGT","LIGHTS","LGTS");
  array_push($streettypearray,"LF","LOCK","LCK","LOCKS","LCKS","LDG","LOOP");
  array_push($streettypearray,"MALL","MANOR","MNR","MNRS","MEADOW","MDW","MEADOWS","MDWS","MEWS","MILL","ML","MILLS","MLS");
  array_push($streettypearray,"MISSION","MSN","MTWY","MOUNT","MNT","MT","MTN","MTNS");
  array_push($streettypearray,"NECK","NCK","ORCHARD","ORCH","OVAL","OVLK","OPAS");
  array_push($streettypearray,"PARK","PARKS","PKWY","PASS","PSGE","PATH","PIKE","PINE","PNE","PINES","PNES","PLACE","PL");
  array_push($streettypearray,"PLAIN","PLN","PLAINS","PLNS","PLAZA","PLZ","POINT","PT","PTE","POINTS","PTS");
  array_push($streettypearray,"POND","PORT","PRT","PORTS","PRTS","PRAIRIE","PR");
  array_push($streettypearray,"RADL","RAMP","RANCH","RNCH","RAPID","RPD","RAPIDS","RPDS","REST","RST","RIDGE","RDG","RIDGES","RDGS");
  array_push($streettypearray,"RIVER","RIV","ROAD","RD","ROADS","RDS","RTE","ROW","RUE","RUN");
  array_push($streettypearray,"SHOAL","SHL","SHOALS","SHLS","SHORE","SHR","SHORES","SHRS","SKWY","SPRING","SPG","SPRINGS","SPGS");
  array_push($streettypearray,"SPUR","SQUARE","SQ","SQUARES","SQS","STA","STRA","STREAM","STRM","STREET","ST","STREETS","STS","SUMMIT","SMT");
  array_push($streettypearray,"TERRACE","TER","TRWY","TRACE","TRCE","TRACK","TRAK","TRFY","TRAIL","TRL","TUNNEL","TUNL","TPKE");
  array_push($streettypearray,"UPAS","UN","UNS","VALLEY","VLY","VALLEYS","VLYS","VIA","VIEW","VW","VIEWS","VWS");
  array_push($streettypearray,"VILLAGE","VLG","VILLAGES","VLGS","VILLE","VL","VISTA","VIS");
  array_push($streettypearray,"WALK","WALL","WAY","WAYS","WL","WLS","WHF");
  $Address = strtoupper($Address); // first uppercase all (to match with PO abbreviations)
  $Address = str_replace(".", "", $Address); // strip periods
  $names_array = explode(' ',$Address); // split words
  $sql = "SELECT * FROM POAddressAbbr";
  // loop through all common words to replace with abbreviations
  if (!$result = mysql_query($sql)) {
    fataldberror("Error reading postal abbreviations!");
  } else {
    while ($row = mysql_fetch_array($result)) {
      $addressfirstword = "N";
      for ($i = 0; $i < count($names_array); $i++) {
          // need to exclude if it's part of the street name (i.e., next part is in list)
          $skipabbr = "N";
          if ($addressfirstword == "N") { // don't abbreviate first word of a street name!
            $skipnumdirectional = "N";
            if (preg_match('#[0-9]#',$names_array[$i])) { $skipnumdirectional = "Y"; }
            foreach ($directionalarray as $x) {if ($x == $names_array[$i]) { $skipnumdirectional = "Y"; } }
            if ($skipnumdirectional == "N") {
                $skipabbr = "Y";
                $addressfirstword = "Y";
            }
          }
          if (array_key_exists($i+1, $names_array)) { // first make sure next part is NOT a street type!
            foreach ($streettypearray as $x) {if ($x == $names_array[$i+1]) { $skipabbr = "Y"; } }
          }
          if (array_key_exists($i-1, $names_array)) { // then make sure last part is NOT County and this part is Road!
            if ($names_array[$i-1] == "COUNTY") {
                if ($names_array[$i] == "ROAD") { $skipabbr = "Y"; }
            }
          }
          if ($skipabbr == "N") {
            if ($names_array[$i] == $row["CommonAddr"]) { $names_array[$i] = $row["POAbbr"]; }
            if ($names_array[$i] == $row["CommonAddr"].",") { $names_array[$i] = $row["POAbbr"].","; }
          }
      }
    }
  }
  // exceptions to case
  for ($i = 0; $i < count($names_array); $i++) {
    $names_array[$i] = strtolower($names_array[$i]); // lowercase all
    if (strncmp($names_array[$i],'mc',2) == 0 || ereg('^[oO]\'[a-zA-Z]',$names_array[$i]) || ereg('^[dD]\'[a-zA-Z]',$names_array[$i])) {
      // fix Mc?, O'?, and D'?
      $names_array[$i][2] = strtoupper($names_array[$i][2]);
    }
    if (strncmp($names_array[$i],'mac',3) == 0 && strncmp($names_array[$i],'mack',4) != 0) {
      // fix Mac?, except for MacK
      $names_array[$i][3] = strtoupper($names_array[$i][3]);
    }
    if (strpos($names_array[$i], "#") === true) {
      // fix # words to be all uppercase
      $names_array[$i] = strtoupper($names_array[$i]);
    }
    if (ereg('^\'',$names_array[$i]) || ereg('^\"',$names_array[$i]) || ereg('^\(',$names_array[$i])) {
       // fix names in ' ', " ", and ( )
       $names_array[$i][1] = strtoupper($names_array[$i][1]);
    }
    $names_array[$i] = ucfirst($names_array[$i]); // uppercase the first letter
  }
  $Address = implode(' ', $names_array); // put address words back together
  // fix hyphenated words
  $names_array = explode('-', $Address); // split hyphenated names
  for ($i = 0; $i < count($names_array); $i++) {
    $names_array[$i] = ucfirst($names_array[$i]); // uppercase the first letter
  }
  $Address = implode('-', $names_array); // put hyphenated names back together
  // fix case exceptions
  $Address = str_replace($casexfindarray, $casexplacearray, $Address); // correct exceptions
}

// Store City with Proper Case
if ($City) {
  $City = strtolower($City); // first lowercase all
  $names_array = explode('-',$City); // split hyphenated names
  for ($i = 0; $i < count($names_array); $i++) {
    if (strncmp($names_array[$i],'mc',2) == 0 || ereg('^[oO]\'[a-zA-Z]',$names_array[$i]) || ereg('^[dD]\'[a-zA-Z]',$names_array[$i])) {
      // fix Mc?, O'?, and D'?
      $names_array[$i][2] = strtoupper($names_array[$i][2]);
    }
    $names_array[$i] = ucfirst($names_array[$i]); // uppercase the first letter
  }
  $City = implode('-',$names_array); // put hyphenated names back together
  $City = ucwords($City); // uppercase each first letter
  $City = str_replace("Ft. ", "Fort ", $City);
  $City = str_replace("Ft ", "Fort ", $City);
}

// Store State with Proper Case (which should be the case)
if ($State) {
  $State = strtoupper($State);
}

// Ensure ZipCode is 5 or 9 Digits (store with a hyphen)
if ($ZipCode) {
  $ZipCode = preg_replace("![^0-9]!", "", $ZipCode); // strips nonnumeric characters
  $length = strlen ($ZipCode);
  if ($length != 5 && $length != 9) {
    if (in_array("ZipCode", $registrationreqfields)) { // don't error if ZipCode is not required or used
      $errormessage .= "Please enter a valid zip code.";
    } else {
      if ($ZipCode) { $errormessage .= "Please enter a valid zip code."; }
    }
  }
  $zip = substr($ZipCode,0,5);
  if (($length == 9)) {
    $zipfour = substr($ZipCode,5,4);
    $array = array( $zip, "-", $zipfour);
    $ZipCode = implode("",$array);
  }
  else {
    $ZipCode = $zip;
  }
}

?>
