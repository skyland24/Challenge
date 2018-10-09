<?php

$username=$argv[1];
$token=$argv[2];


if(count($argv)<3){
echo "\n You need to run the script in the format: githubreader.php githubUserName gihubToken \n";
die(1);
}


function github_request($url,$usrname,$usertoken)
{
    $ch = curl_init();    
    // Basic Authentication with token
    // https://developer.github.com/v3/auth/
    // https://github.com/blog/1509-personal-api-tokens
    // https://github.com/settings/tokens
    $access = "$username:$usertoken";
    
    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
    curl_setopt($ch, CURLOPT_USERAGENT, 'githubreader');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERPWD, $access);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);
    curl_close($ch);
    $result = json_decode(trim($output), true);
    return $result;
}

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('github.db');
    }
}




$db = new MyDB();


#/repos/:owner/:repo/commits
$memberfilecontent=file_get_contents("memebers.json");
$r=github_request("https://api.github.com/repos/BendingSpoons/katana-swift/commits?until=2018-06-27T23:59:59Z",$username,$token);
$db->exec("delete from commits");
foreach($r as $commit){
$external=1;
	$commiter_login=$commit["committer"]["login"];
	$commiter_msg=$commit["commit"]["message"];
        $commiter_sha=$commit["sha"];
	$commiter_date=$commit["commit"]["committer"]["date"];

	if(!preg_match("/($commiter_login)/",$memberfilecontent)){
		$external=0;
 	}
	echo "$commiter_login | $commiter_sha | $commiter_msg | $commiter_date | $external \n";
	$db->exec("INSERT INTO commits (`sha`,`date`,`author`,`message`,`is_external`) values ('$commiter_sha','$commiter_date','$commiter_login','$commiter_msg','$external') ");
}



?>
