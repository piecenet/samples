<?php
/**********************************************************************
 * Get Address Parts (Parse Address)                                  *
 * Version 2013.0000                                                  *
 * (C)2013 A Piece of the Net                                         *
 **********************************************************************/

// This expects the $Address to be defined and parses it.
// This assumes userdatacleanup.php has already fixed the address with common abbreviations!
// It creates and sends output to $streetarraycachecache (as an HTML table).
// It creates and uses several variables that start with $gap.

// Start with empty address parts! (These are the part variables wanted!)
{
  $gapaddresspart = explode(" ", $Address);
  $gapstreetnumber = "";
  $gappredirectional = "";
  $gapstreetname = "";
  $gappostdirectional = "";
  $gapstreettype = "";
  $gapunit = "";
}

// Define arrays for part values.
{
  // Instead of the arrays below, make these a flag in StreetAbbr table!
  $gapdirectionalarray = array("N","S","E","W","NE","NW","SE","SW");
  $gapstreettypearray = array("ALY","ARC","AVE","BYU","BCH","BND","BLF","BLFS","BLVD","BR","BRG","BRK","BRKS","BG","BGS","BYP");
  array_push($gapstreettypearray,"CP","CYN","CPE","CSWY","CTR","CTRS","CHSE","CIR","CIRS","CRK","CLF","CLFS","CLB","CMN","CMNS","COR","CORS","CRSE");
  array_push($gapstreettypearray,"CT","CTS","CV","CVS","CRES","CRST","XING","XRD","XRDS","CURV","DL","DM","DV","DR","DRS","EST","ESTS","EXPY","EXTS");
  array_push($gapstreettypearray,"FALL","FLS","FRY","FLD","FLDS","FLT","FLTS","FRD","FRDS","FRST","FRG","FRGS","FRK","FRKS","FT","FWY");
  array_push($gapstreettypearray,"GDN","GDNS","GTWY","GLN","GLNS","GRN","GRNS","GRV","GRVS","HBR","HBRS","HVN","HTS","HWY","HL","HLS","HOLW");
  array_push($gapstreettypearray,"INLT","IS","ISS","ISLE","JCT","JCTS","KY","KYS","KNL","KNLS");
  array_push($gapstreettypearray,"LK","LKS","LAND","LNDG","LN","LGT","LGTS","LF","LCK","LCKS","LDG","LOOP");
  array_push($gapstreettypearray,"MALL","MNR","MNRS","MDW","MDWS","MEWS","ML","MLS","MSN","MTWY","MT","MTN","MTNS","NCK","ORCH","OVAL","OVLK","OPAS");
  array_push($gapstreettypearray,"PARK","PKWY","PASS","PSGE","PATH","PIKE","PNE","PNES","PL","PLN","PLNS","PLZ","PT","PTE","PTS","POND","PRT","PRTS","PR");
  array_push($gapstreettypearray,"RADL","RAMP","RNCH","RPD","RPDS","RST","RDG","RDGS","RIV","RD","RDS","RTE","ROW","RUE","RUN");
  array_push($gapstreettypearray,"SHL","SHLS","SHR","SHRS","SKWY","SPG","SPGS","SPUR","SQ","SQS","STA","STRA","STRM","ST","STS","SMT");
  array_push($gapstreettypearray,"TER","TRWY","TRCE","TRAK","TRFY","TRL","TUNL","TPKE","UPAS","UN","UNS");
  array_push($gapstreettypearray,"VLY","VLYS","VIA","VW","VWS","VLG","VLGS","VL","VIS","WALK","WALL","WAY","WAYS","WL","WLS","WHF");
  $gapunitarray = array("APT","BSMT","BTM","BLDG","DEPT","FL","FRNT","LBBY","LOWR","#","OFC","PH","RM","SPC","STE","TRLR","UPPR");
  $gapunitprearray = array("APT","BOX","#","RM","SPC","STE");
  $gapunitnextarray = array("BLDG","DEPT","FL"); // not positive about these! (also pre?)
  // Unknown if bottom and trailer is a unit or street type?
}

// Assign address parts based on location and value.
foreach($gapaddresspart as $gapi => $gapvalue) {
  $gapiassigned = "N";
  $gapvalue = trim($gapvalue,","); // get rid of comma
  if ($gapi == 0) { // first word is street number if it has numbers
    if (preg_match('#[0-9]#',$gapvalue)) { $gapstreetnumber = $gapvalue; $gapiassigned = "Y"; }
  }
  $gaplasti = $gapi - 1;
  if (array_key_exists("$gaplasti", $gapaddresspart)) {
    foreach ($gapunitprearray as $gapx) { // add to unit if last word is a unit designation
      // added strtoupper to fix capitalization issues - delete note if no issues
      if ($gapx == strtoupper($gapaddresspart[$gaplasti])) { $gapunit .= " $gapvalue"; $gapiassigned = "Y"; }
    }
  }
  $gapnexti = $gapi + 1;
  if (array_key_exists("$gapnexti", $gapaddresspart)) {
    foreach ($gapunitnextarray as $gapx) { // add to unit if next word is a post unit designation
      if ($gapx == $gapaddresspart[$gapnexti]) { $gapunit .= "$gapvalue"; $gapiassigned = "Y"; }
    }
  }
  if ($gappredirectional == "" && $gapstreetname == "") {
    foreach ($gapdirectionalarray as $gapx) { // is word likely a predirectional?
      if ($gapx == $gapvalue) { $gappredirectional = "$gapvalue"; $gapiassigned = "Y"; }
    }
  }
  if ($gapstreetname == "" && $gapiassigned == "N") { // if not yet assigned, word is street name
    $gapstreetname = "$gapvalue"; $gapiassigned = "Y";
  }
  if ($gappostdirectional == "" && $gapstreetname != "") {
    foreach ($gapdirectionalarray as $gapx) {if ($gapx == $gapvalue) { // is word likely a postdirectional?
      $gappostdirectional = "$gapvalue"; $gapiassigned = "Y"; }
    }
  }
  if ($gapstreettype == "" && $gapstreetname != "" && $gapiassigned == "N") { // if not yet assigned
    if (array_key_exists("$gapnexti", $gapaddresspart)) { // add to street name if next 2 parts are a street type!
      foreach ($gapstreettypearray as $gapx) {
        // added strtoupper to fix capitalization issues - delete note if no issues
        if ($gapx == strtoupper($gapaddresspart[$gapnexti])) { $gapstreetname .= " $gapvalue"; $gapiassigned = "Y"; }
        elseif (isset($gapaddresspart[$gapnexti+1])) {
          if ($gapx == strtoupper($gapaddresspart[$gapnexti+1])) { $gapstreetname .= " $gapvalue"; $gapiassigned = "Y"; }
        }
      }
    }
    if (array_key_exists("$gaplasti", $gapaddresspart)) { // add to street name if last part is county & this part is Road!
      // added strtoupper to fix capitalization issues - delete note if no issues
      if (strtoupper($gapaddresspart[$gaplasti]) == "County") {
        if (strtoupper($gapaddresspart[$gapi]) == "Road") { $gapstreetname .= " $gapvalue"; $gapiassigned = "Y"; }
      }
    }
    if ($gapiassigned == "N") {
      foreach ($gapstreettypearray as $gapx) { // add to street type if not yet assigned and matches as street type
        // added strtoupper to fix capitalization issues - delete note if no issues
        if ($gapx == strtoupper($gapvalue)) { $gapstreettype = "$gapvalue"; $gapiassigned = "Y"; }
      }
    }
  }
  if ($gapstreetname != "" && $gapiassigned == "N") {
    // added strtoupper to fix capitalization issues
    foreach ($gapunitarray as $gapx) { // if still not assigned is next word a unit
      if ($gapx == strtoupper($gapvalue)) { $gapunit .= "$gapvalue"; $gapiassigned = "Y"; }
    }
  }
  if (substr($gapvalue, 0, 1) == "#") { $gapunit .= "$gapvalue"; $gapiassigned = "Y"; } // assumed # is part of unit
  if ($gappostdirectional == "" && $gapstreettype == "" && $gapiassigned == "N") { // add to street name if no post yet
    $gapstreetname .= " $gapvalue"; $gapiassigned = "Y";
  }
  // if not yet assigned, it just silently drops the part - not sure what part it could be...
}

// Create $streetarraycachecache (as an HTML table).
$streetarraycache = "<table align=\"center\"><tr><td><p>\n";
if ($gapstreetnumber != "") { $streetarraycache .= "Street Number: $gapstreetnumber<br />\n"; }
if ($gappredirectional != "") { $streetarraycache .= "Pre Directional: $gappredirectional<br />\n"; }
if ($gapstreetname != "") { $streetarraycache .= "Street Name: $gapstreetname<br />\n"; }
if ($gappostdirectional != "") { $streetarraycache .= "Post Directional: $gappostdirectional<br />\n"; }
if ($gapstreettype != "") { $streetarraycache .= "Street Type: $gapstreettype<br />\n"; }
if ($gapunit != "") { $streetarraycache .= "Unit: $gapunit<br />\n"; }
$streetarraycache .= "</p></td></tr></table>\n\n";
?>
