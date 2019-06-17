# Formula1
##Simple Mathematical formulas evaluator for PHP

If you need to evaluate a formula with basic operators and variables, and

- You dont want to use EVAL for obvious security reasons,
- You don't want to rely on half the internet as dependancies, but prefer a single self-contained class,
- You don't necessarily have the latest PHP version,
- You want something readable that you could easily understand and customize,

Then you might like Formula1 ! ;-)


**Simple usage no variables**
$f1 = new Formula1('-45+(41*2)-(6/(3+1))');
echo($f1->compute()); // 35.5

**Simple usage with variables**
$f1 = new Formula1('-45+(fourty_one*2)-(6/(three+1))' , ['fourty_one'=>41, 'three'=>3]);
echo($f1->compute());

**Funky usage with variables that include formulas themselves (and recusrively)**
$f1 = new Formula1('-45+(fourty_one*2)-(6/(three+1))' , ['fourty_one'=>'82/2', 'three'=>'12/four', 'four'=>4]);
echo($f1->compute());



