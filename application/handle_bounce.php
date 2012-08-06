#!/usr/local/bin/php -q
<?PHP
//============================================================+
// Version     : 1.0
// License     : GNU GPL (http://www.gnu.org/licenses/gpl-3.0.html)
// 	----------------------------------------------------------------------------
//  Copyright (C) 2010-2012 Jonathan Romley - Xebura Corporation
//
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//
// 	This program is distributed in the hope that it will be useful,
// 	but WITHOUT ANY WARRANTY; without even the implied warranty of
// 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// 	GNU Lesser General Public License for more details.
//
// 	You should have received a copy of the GNU Lesser General Public License
// 	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 	See LICENSE.TXT file for more information.
//  ----------------------------------------------------------------------------
//
// Description : PHP-based multitenant marketing automation software
//               using Amazon Simple Email Service and Twilio
//
// Author: Jonathan Romley
//
// (c) Copyright:
//               Jonathan Romley
//               Xebura Corporation
//               256 South Robertson Blvd
//               Beverly Hills, CA 90211
//               USA
//               www.xebura.com
//               j@xebura.com
//============================================================+

// Reading in the email
$fd = fopen("php://stdin", "r");
while (!feof($fd)) {
  $email .= fread($fd, 1024);
}
fclose($fd);

// Parsing the email
$lines = explode("\n", $email);
$stillheaders=true;
for ($i=0; $i < count($lines); $i++) {
  if ($stillheaders) {
    // this is a header
    $headers .= $lines[$i]."\n";

    // look out for special headers
    if (preg_match("/^Subject: (.*)/", $lines[$i], $matches)) {
      $subject = $matches[1];
    }
    if (preg_match("/^From: (.*)/", $lines[$i], $matches)) {
      $from = $matches[1];
    }
    if (preg_match("/^To: (.*)/", $lines[$i], $matches)) {
      $to = $matches[1];
    }
  } else {
    // not a header, but message
    break;
    // Optionally you can read out the message also, instead of the break:
    //$message .= $lines[$i]."\n";
  }

  if (trim($lines[$i])=="") {
    // empty line, header section has ended
    $stillheaders = false;
  }
}

list($part1,$dum1) = explode("-bounce@xebura.com", trim($to) ); 
list($dum2,$user) = explode("user-", $part1);
//
// $user now contains the user id "12345" in the example
//
// Here you put in your custom code 
// like open up your database connection and 
// mark the user as invalid email address,
// so you don's send to him again.

return true;
?>