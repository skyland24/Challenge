# What u need for the scritp to run!

Firs of all to run this script une need:

1. To have github account.
2. A token that can connect with github.
3. Php compiler
4. Sqlite3 library to create  and read DB

# The  Script!!

As mention the script is in php and  request github for the commit until the specific date (the date is with your decision to change)

# $r=github_request("https://api.github.com/repos/BendingSpoons/katana-swift/commits?until=2018-06-27T23:59:59Z",$username,$token);

After that all the responde that github  give are compared with the json file that we have  for the member of the specific github account.

foreach($r as $commit){
$external=1;
	$commiter_login=$commit["committer"]["login"];
	$commiter_msg=$commit["commit"]["message"];
        $commiter_sha=$commit["sha"];
	$commiter_date=$commit["commit"]["committer"]["date"];

	if(!preg_match("/($commiter_login)/",$memberfilecontent)){
		$external=0;


The result  from the comparison of the request to github and the json file are stored in a .db file created with sqlite3 library.

echo "$commiter_login | $commiter_sha | $commiter_msg | $commiter_date | $external \n";
	$db->exec("INSERT INTO commits (`sha`,`date`,`author`,`message`,`is_external`) values ('$commiter_sha','$commiter_date','$commiter_login','$commiter_msg','$external') ");

