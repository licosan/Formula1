# Formula1
## Simple Mathematical formulas evaluator for PHP

If you need to evaluate a formula with basic operators and variables, and

* **No eval:** You dont want to use EVAL for obvious security reasons,
* **No dependencies:** You don't want to rely on half the internet as dependencies, but prefer a single self-contained class,
* **Old PHP OK:** You don't necessarily have the latest PHP version, or Composer,
* **No spaghetti:** You want something readable that you could easily understand and customize,
* **Arithmetic rules OK:** You need something that knows about operators priority, unary operators, and parenthesis,
* **Rounding OK:** You want to specify a precision and rounding mode for the result,
* **Recursive eval:** You want to be able to include sub-formulas inside your variables,
* **Arrays OK:** You need to use indexed or key-value arrays as variable inside the formula,

...then you gonna like Formula1 ! ;-)



**Simple usage no variables**

```PHP
$f1 = new Formula1('-45+(41*2)-(6/(3+1))');
echo($f1->compute()); // 35.5
```

```PHP
// With rounding (third and fourth params are precision and mode, like for the round function) 
$f1 = new Formula1('2/3', [], 3);
echo($f1->compute()); // 0.667
```

**Simple usage with variables**

```PHP
$f1 = new Formula1('-45+(fourty_one*2)-(6/(three+1))' , ['fourty_one'=>41, 'three'=>3]);
echo($f1->compute()); // 35.5
```

**Funky usage with variables that include formulas themselves (and recusrively)**

```PHP
$f1 = new Formula1('-45+(fourty_one*2)-(6/(three+1))' , ['fourty_one'=>'82/2', 'three'=>'12/four', 'four'=>4]);
echo($f1->compute()); // 35.5
```

**Display parsing error if any**

```PHP
$f1 = new Formula1('-45+(fourty_one*2)-(6/(three+1)))' , ['fourty_one'=>'82/2', 'three'=>'12/four', 'four'=>4]);
if($f1->parse_error=='') echo($f1->compute()); 
else echo($f1->parse_error."<br>\n"); // Too many closing parenthesis !
```

**Use of a indexed array**
```PHP
$f1 = new Formula1('toto*primes[6]',['toto'=>10,'primes'=>[2,3,5,7,11,13,17,19,23,29,31]]);
echo($f1->compute()."<br>\n"); //170
```

**Use of a key-value array**
```PHP
$f1 = new Formula1('toto*myar["zorglub_99"]',['toto'=>10,'myar'=>['aaa'=>2.5,'zorglub_99'=>5.5]]);
echo($f1->compute()."<br>\n"); //55
```

## TODO

* Add math operations like sin, cos, sqrt etc...
* Would be better to give parameters in the compute, to allow one parsing-many evals, but needs rewrite of current parse-compute split.
* The index of indexed arrays should be evaluated to allow formulas as indexes, but needs rewrite of current parse-compute split.
