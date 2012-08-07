Frig Monitor is a Nagios / Cacti presentation module for Drupal 7.
	http://rx-nagios.kungfootek.net/frigg/dashboard/ admini \ biggles

Dependencies:
Nagios 
NagiosGraph
Nagios Looking Glass ( Pain to install )
Friggs Monitor & Prophecy. ( Both in Repo )
Cacti ( Optional )

Requirements:
This document assumes the reader is familiar with, or capable of installing Drupal, Nagios, NagiosGraph, Nagios Looking Glass, and operating at the Linux command line to create Symlinks and operate Git, etc.

Frigg Monitor relies on another set of scripts called Friggs Prophecy. Prophecy is critical for client servers where we cannot have absolute control over SNMP.  Prophecy Scripts and sample configurations are found in the 'ACC' folder of Frigg_Monitor.  

Process Flow: 
Nagios checks hosts with Friggs_Prophecy Command →
NagiosGraph process performance data returned by Friggs_Prophecy ←
Drupal Frigg_Monitor calls NLG for its Host list, and Displays graphs in the dashboard.

Installation:
On the Host machine running nagios, and NLG:

Install Drupal 7, Nagios, NLG and verify NLG is showing new hosts as entered into Nagios. 

The Git repo lives on the server under a user account on one location and syn-links are used to provide access. 

Create the Frigg user on the host and client computers. You will store the frigg module files under this user, and create symlinks for local execution as well as execute these scripts remotely. Run ssh-keygen and paste your public key to your github account.

In the user folder create a dev folder and change into it. 

Clone the Frigg_Monitor repo here. Your path should be as follows: 
/home/<user> /home/<user>/dev/frigg_monitor/

In the Nagios plugins folder, on the Nagios Host.
Cd  /usr/lib/nagios/plugins/

Create the symlink 
'ln -s'  /home/<user>/dev/frigg_monitor/acc/nagios-plugins/check_prophecy.php check_prophecy.php

'ls -l' should show:
check_prophecy.php ->  /home/<user>/dev/frigg_monitor/acc/nagios-plugins/check_prophecy.php

“Install” the drupal module, by creating a symlink from the module folder of choice to your github repo. 
Ln -s /home/<user>/dev/frigg_monitor   frigg_monitor

'ls -l' should show: 
frigg_monitor -> /home/<user>/dev/frigg_monitor

Append the rrd mappings so nagiosgraph can display the results. 
The template is found in the 'acc' folder under rrd-map. These can use improvement, and then the rest of the mysql-status reports added. 

---
# Service type: Frigg Prophecy - Full_Monty

# Queries: 609302030 Connections: 192460732 Bytes_received: 179 Bytes_sent: 558 

/Queries: (\d+)  Connections: (\d+)  Bytes_received: (\d+)  Bytes_sent: (\d+)/

and push @s, [ 'Prophecy',

                [ 'Queries', COUNTER, $1 ],

                [ 'Connections', COUNTER, $2 ],

                [ 'Bytes_Received', COUNTER, $3],

                [ 'Bytes_Sent', COUNTER, $4] ];


---
Work to do on the host For Remote Execution of Prophecy:
Edit the nagios user in /etc/passwd and give it /bin/bash enabling it to login.
Change to the nagios user:  'Su -  nagios'
Create the ssh key pair: ssh-keygen
We will come back to this later: Exit


---
On the Client Machine being checked:

Nagios calls the 'check_prophecy.php' script which calls 'frigg-prophecy.php' via SSH or
 by SNMP.

The SNMP call requires support on the target host for extend, exec, or pass_persist. Pantheon does not support these SNMP options so we do the same call with the more secure ssh remote execute flag '-n'. in line 11 of 'check_prophecy.php'

$result=shell_exec("ssh -n -ouser=root $Argz[0] /root/dev/frigg_monitor/acc/frigg-prophecy.php $Argz[1]");

$Argz[0] is the host specified by the host variable in Nagios
$Argz[1] is the command nagios will extend to frigg_prophecy. In most cases, this will be 'full_monty' for “everything”. For now this is only 4 items. There is Much more yet to be included. 

On the client host being checked by nagios:
As root in the root home folder, create a dev folder and change to it. 

Create another symlink.
Ln -s /home/<user>/dev/frigg_monitor  frigg_monitor

Why Root? 
1. I wanted one place to call from a remote location. It can be anywhere. Just make sure you change line 11 shown above to point to that other folder. It would make sense to standardize on one user name. Perhaps that name can be frigg. 
2. When working with Git keys you may come across a situation where the root key for the server has been used for other projects and you will only be able to check out with the key you created for frigg. 
3. This was the first place I started developing this project on my local machine, and I havent had the chance to go back and do the normal house cleaning. 


Establish your ssh connection:
	This is an important step, 
	do not proceed unless you can establish an ssh connection to the client, and access 'frigg-prophecy.php'.

On the host machine, copy the public ssh key for the nagios user, and append it to roots ( or friggs) authorized_keys2 file on the client machine being checked. 
change to the nagios user: 'Su -  nagios' and test the key / accept the server key and store it. This is how frigg performs its check, and returns performance data.


Notes:
It takes about half an hour for nagios / nagiosgraph to begin displaying useful graphs. Have patience. 
When you enter new hosts in nagios, give it a couple minutes to show in Druapl. 


Resources:

Nagios:
http://nagios.org/

PCMi Nagios: 
http://rx-nagios.kungfootek.net/nagios3/ nagiosadmin \  N0kiaSlav#

PCMi Nagios config Interface:
http://rx-nagios.kungfootek.net/nagiosql/ admin \ biggles


Nagios Looking Glass:
http://exchange.nagios.org/directory/Addons/Frontends-(GUIs-and-CLIs)/Web-Interfaces/Nagios-Looking-Glass/details

Frigg_Monitor
https://github.com/PCMi/frigg_monitor/tree/master/acc 


Extra Reading:
SSH Key handling:
http://www.thegeekstuff.com/2008/11/3-steps-to-perform-ssh-login-without-password-using-ssh-keygen-ssh-copy-id/

RRD Howto: 
http://oss.oetiker.ch/rrdtool/tut/rrdtutorial.en.html

GIT Howto:
https://help.github.com/articles/set-up-git


