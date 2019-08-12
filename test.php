<?php

require_once('../override/classes/Formula1.1.php');

echo('<hr>');
$f1 = new Formula1('-45+(41*2)-(6/(3+1))'); 
if($f1->parse_error=='') echo('35.5 == '.$f1->compute()."<br>\n"); 
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('2/3', [], 3);
if($f1->parse_error=='') echo('0.667 == '.$f1->compute()."<br>\n"); // 0.667
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('-45+(fourty_one*2)-(6/(three+1))' , ['fourty_one'=>41, 'three'=>3]);
if($f1->parse_error=='') echo('35.5 == '.$f1->compute()."<br>\n"); 
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('-45+(fourty_one*2)-(6/(three+1))' , ['fourty_one'=>'82/2', 'three'=>'12/four', 'four'=>4]);
if($f1->parse_error=='') echo('35.5 == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('5*2*sin(pi/4)' , ['pi'=>'3.141592654']);
if($f1->parse_error=='') echo('7.071... == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('1+9+sin(pi/4)' , ['pi'=>'3.141592654']);
if($f1->parse_error=='') echo('10.7071... == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('2*5+sin(pi()/4)');
if($f1->parse_error=='') echo('10.7071... == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('rand()');
if($f1->parse_error=='') echo('?rand? == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('rand(5)');
if($f1->parse_error=='') echo('?rand 0->5 ? == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('-45+badfunc(fourty_one*2)-(6/(three+1))' , ['fourty_one'=>'82/2', 'three'=>'12/four', 'four'=>4]);
if($f1->parse_error=='') echo('You should not see me !! '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n");

echo('<hr>');
$f1 = new Formula1('-(3+2+1)/2');
if($f1->parse_error=='') echo('-3 == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('toto*myar["zorglub"]',['toto'=>10,'myar'=>['aaa'=>2.5,'zorglub'=>5.5]]);
if($f1->parse_error=='') echo('55 == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('toto*badar["aaa"]',['toto'=>10,'myar'=>['aaa'=>2.5,'zorglub'=>5.5]]);
if($f1->parse_error=='') echo('You should not see me !! : '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('toto*badar["aaa"]',['toto'=>10,'badar'=>5]);
if($f1->parse_error=='') echo('You should not see me !! : '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('toto*myar["zzz"]',['toto'=>10,'myar'=>['aaa'=>2.5,'zorglub'=>5.5]]);
if($f1->parse_error=='') echo('You should not see me !! : '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('toto*myar[2]',['toto'=>10,'myar'=>[5,10,15,20,25]]);
if($f1->parse_error=='') echo('150 == '.$f1->compute()."<br>\n");
else echo($f1->parse_error."<br>\n"); 

echo('<hr>');
$f1 = new Formula1('toto*primes[6]',['toto'=>10,'primes'=>[2,3,5,7,11,13,17,19,23,29,31]]);
echo($f1->compute()."<br>\n"); //170
