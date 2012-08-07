<?php

/*
 * @file
 * Created to take the non-drupal stuff out of the module itsel
 *
 */

class frigg_monitor {



  public function frigg_server_summary($ServerName, $ServerStatus, $ServiceCount, $ServiceOK, $ServiceWarn, $ServiceFail) {

    $Domain=$_SERVER['SERVER_NAME'];
    //  Later these be be modified by CSS.
    if ($ServerStatus == "OK") {
      $ServerNameStatusOutput="<font color=green>$ServerName</font>";
    }
    else {
      $ServerNameStatusOutput="<font color=red>$ServerName</font>";
    }


    $ServerNameStatusOutputLink = "<a href=\"http://$Domain/frigg/nagios/$ServerName\">";

    $output  = "<table border=0>";
    $output .= "<tr><td align=left><b>$ServerNameStatusOutputLink$ServerNameStatusOutput</a></b></td><td align=right>$ServerStatus</td></tr>";
    $output .= "<Tr><td>Service State</td><td>&nbsp;</td></tr>";
    $output .= "<Tr><td><font color=\"green\">OK</font></td><td align=right><font color=\"green\"></font>$ServiceOK</font></td></tr>";
    $output .= "<Tr><td><font color=\"orange\">Warning</font></td><td align=right><font color=\"orange\">$ServiceWarn</font></td></tr>";
    $output .= "<Tr><td><font color=\"red\">Failed</font></td><td align=right><font color=\"red\">$ServiceFail</font></td></tr>";
    $output .= "</table>";
    return $output;
  }

}