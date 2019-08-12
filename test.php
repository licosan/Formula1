<?php

require_once('../override/classes/Formula1.1.php');

$f1 = new Formula1('-45+(41*2)-(6/(3+1))'); 
if($f1->parse_error=='') echo('35.5 == '.$f1->compute()."<br>\n"); 
else echo($f1->parse_error."<br>\n"); 

$f1 = new Formula1('2/3', [], 3);
if($f1->parse_error=='') echo('0.667 == '.$f1->compute()."<br>\n"); // 0.667
else echo($f1->parse_error."<br>\n"); 

$f1 = new Formula1('-45+(fourty_one*2)-(6/(three+1))' , ['fourty_one'=>41, 'three'=>3]);
if($f1->parse_error=='') echo('35.5 == '.$f1->compute()."<br>\n"); 
else echo($f1->parse_error."<br>\n"); 

$f1 = new Formula1('-45+(fourty_one*2)-(6/(three+1))' , ['fourty_one'=>'82/2', 'three'=>'12/four', 'four'=>4]);
if($f1->parse_error=='') echo('35.5 == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

$f1 = new Formula1('-(3+2+1)/2');
if($f1->parse_error=='') echo('-3 == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

$f1 = new Formula1('toto*myar["zorglub"]',['toto'=>10,'myar'=>['aaa'=>2.5,'zorglub'=>5.5]]);
if($f1->parse_error=='') echo('55 == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

$f1 = new Formula1('toto*badar["aaa"]',['toto'=>10,'myar'=>['aaa'=>2.5,'zorglub'=>5.5]]);
if($f1->parse_error=='') echo('You should not see me !! : '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

$f1 = new Formula1('toto*badar["aaa"]',['toto'=>10,'badar'=>5]);
if($f1->parse_error=='') echo('You should not see me !! : '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

$f1 = new Formula1('toto*myar["zzz"]',['toto'=>10,'myar'=>['aaa'=>2.5,'zorglub'=>5.5]]);
if($f1->parse_error=='') echo('You should not see me !! : '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

$f1 = new Formula1('toto*myar[2]',['toto'=>10,'myar'=>[5,10,15,20,25]]);
if($f1->parse_error=='') echo('150 == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

