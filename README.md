# Formula1
## Simple Mathematical formulas evaluator for PHP

If you need to evaluate a formula with basic operators and variables, and

* **No eval:** You dont want to use EVAL for obvious security reasons,
* **No dependencies:** You don't want to rely on half the internet as dependencies, but prefer a single self-contained class,
* **Old PHP OK:** You don't necessarily have the latest PHP version, or Composer,
* **No spaghetti:** You want something readable that you could easily understand and customize,
* **Arithmetic rules OK:** You need something that knows about operators priority, unary operators, and parenthesis,
* **Recursive eval:** You want to be able to include sub-formulas inside your variables,

...then you might like Formula1 ! ;-)


**Simple usage no variables**

```PHP
$f1 = new Formula1('-45+(41*2)-(6/(3+1))');
echo($f1->compute()); // 35.5
```

```PHP
// With rounding (third and fourth params are precision and mode, like for the round function) 
$f1 = new Formula1('2/6', [], 3);
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

## TODO

* add math operations like sin, cos, sqrt etc...
