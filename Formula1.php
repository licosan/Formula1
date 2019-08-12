<?php

class Formula1 {
	public $tree=[];
	public $parse_error='';
	private $dual_hp_ops = ['/','*','%','^'];
	private $dual_lp_ops = ['+','-'];
	private $single_ops = ['+','-'];
	private $functions =   ['abs','acos','acosh','asin','asinh','atan','atanh','ceil',
							'cos','cosh','deg2rad','exp','expm1','floor','getrandmax',
							'lcg_value','log10','log1p','log',
							'mt_getrandmax','mt_rand','pi','rad2deg','rand','round',
							'sin','sinh','sqrt','tan','tanh'];

	public function __construct($formula, $params=[], $precision=null, $precision_mode=PHP_ROUND_HALF_UP) {
		$this->params = $params;
		$this->precision = $precision;
		$this->precision_mode = $precision_mode;
		$f = $this->replace_parenth($formula, 0);
		if($this->parse_error == ''){
			if(substr_count(')',$f) > substr_count('(',$f) ) {
				$this->parse_error = 'PARSING ERROR: Too many closing parenthesis !';
			} else if(substr_count(')',$f) < substr_count('(',$f) ) {
				$this->parse_error = 'PARSING ERROR: Too many opening parenthesis !';
			}
			$this->tree = $this->parse($f);			
		}
	}

	private function do_single_op($op, $x){
		switch($op){
			case '+': return($x);
			case '-': return(-$x);
		}
	}

	private function do_function($fn, $x){
		switch($fn){
			case 'abs': return(abs($x));
			case 'acos': return(acos($x));
			case 'acosh': return(acosh($x));
			case 'asin': return(asin($x));
			case 'asinh': return(asinh($x));
			case 'atan': return(atan($x));
			case 'atanh': return(atanh($x));
			case 'ceil': return(ceil($x));
			case 'cos': return(cos($x));
			case 'cosh': return(cosh($x));
			case 'deg2rad': return(deg2rad($x));
			case 'exp': return(exp($x));
			case 'expm1': return(expm1($x));
			case 'floor': return(floor($x));
			case 'getrandmax': return(getrandmax());
			case 'lcg_value': return(lcg_value($x));
			case 'log10': return(log10($x));
			case 'log1p': return(log1p($x));
			case 'log': return(log($x));
			case 'mt_getrandmax': return(mt_getrandmax());
			case 'mt_rand': if($x!='') return(mt_rand(0,$x)); else return(mt_rand());
			case 'pi': return(pi());
			case 'rad2deg': return(rad2deg($x));
			case 'rand': if($x!='') return(rand(0,$x)); else return(rand());
			case 'round': return(round($x));
			case 'sin': return(sin($x));
			case 'sinh': return(sinh($x));
			case 'sqrt': return(sqrt($x));
			case 'tan': return(tan($x));
			case 'tanh': return(tanh($x));
		}
	}

	private function do_dual_op($x, $op, $y){
		switch($op){
			case '+': return($x+$y);
			case '-': return($x-$y);
			case '*': return($x*$y);
			case '/': return($x/$y);
			case '%': return($x%$y);
			case '^': return($x**$y);
		}
	}

	private function find_closingp($f, $i0){
		if(substr($f,$i0,1)!='(') return(false);
		$level = 0;
		for($i=$i0+1;$i<strlen($f);$i++) {
			$c = substr($f,$i,1);
			if($c== '(') $level++;
			if($c==')') {
				if($level==0) return($i);
				$level--;
			}
		}
		return(false);
	}

	private function replace_parenth($f, $expr_index){
		$allops = array_merge($this->dual_lp_ops, $this->dual_hp_ops, $this->single_ops);
		$nf='';
		$last_op_idx = -1;
		for($i=0;$i<strlen($f);$i++) {
			$c = substr($f,$i,1);
			if($c=='(') {
				if( ($i>$last_op_idx+1) && (!in_array(substr($f,$last_op_idx+1,$i-$last_op_idx-1), $this->functions)) ) {
						$this->parse_error = "PARSING ERROR: Unknown function (".substr($f,$last_op_idx+1,$i-$last_op_idx-1).") !";
						return('');						
				}
				$closing_idx = $this->find_closingp($f, $i);
				if($closing_idx===false) {
					$this->parse_error = "PARSING ERROR: missing closing parenthesis !";
					return('');
				}
				$expr = substr($f, $i+1, $closing_idx-$i-1);
				$expr_name = 'expr_'.$expr_index++;
				if($i>$last_op_idx+1) { //functions : keep parenthesis
					$nf.='('.$expr_name.')';
				} else { // expression: remove parenthesis
					$nf.=$expr_name;
				}
				$this->params[$expr_name] = $this->replace_parenth($expr, $expr_index);
				$i =  $closing_idx; // let the loop do the +1
			} else {
				if(in_array($c, $allops)) $last_op_idx=$i;
				$nf .= $c;
			}
		}
		return($nf);
	}


	private function parse($f){	
		$re = '/^(.+?)([\\'.implode('\\',$this->dual_lp_ops).'])(.+)$/'; // dual low priority op
		if(preg_match($re, $f, $matches, PREG_OFFSET_CAPTURE, 0)){ 
			$recur = $this->parse($matches[3][0]);
			if($recur!==false) return([$this->parse($matches[1][0]), $matches[2][0], $recur]);
			else return(false);
		} else {
			$re = '/^(.+?)([\\'.implode('\\',$this->dual_hp_ops).'])(.+)$/'; // dual high priority op
			if(preg_match($re, $f, $matches, PREG_OFFSET_CAPTURE, 0)){ 
				$recur = $this->parse($matches[3][0]);
				if($recur!==false) return([$this->parse($matches[1][0]), $matches[2][0], $recur]);
				else return(false);
			} else {
				$re = '/^([\\'.implode('\\',$this->single_ops).'])(.+)$/'; // single op
				if(preg_match($re, $f, $matches, PREG_OFFSET_CAPTURE, 0)){ 
					$recur = $this->parse($matches[2][0]);
					if($recur!==false) return([null, $matches[1][0], $recur]);
					else return(false);
				} else {				
					$re = '/^'.implode('|', array_map(function($e){ return($e.'\((.*)\)'); }, $this->functions) ).'$/'; // functions
					if(preg_match($re, $f, $matches, PREG_OFFSET_CAPTURE, 0)){ 
						$j=-1; for($i=1;$i<count($matches);$i++){ if($matches[$i][1]>-1) { $j=$i; break; } };
						$recur = $this->parse($matches[$j][0]);
						if($recur!==false) return([null, $this->functions[$j-1], $recur]);
						else return(false);
					} else {
						$re1 = '/^([A-Za-z][A-Za-z0-9_]*)\[\"([A-Za-z0-9_]+)\"\]$/'; // arrays with alphanum keys
						$re2 = "/^([A-Za-z][A-Za-z0-9_]*)\[\'([A-Za-z0-9_]+)\'\]$/"; // arrays with alphanum keys
						if(preg_match($re1, $f, $matches1, PREG_OFFSET_CAPTURE, 0)||preg_match($re2, $f, $matches2, PREG_OFFSET_CAPTURE, 0)){ 
							$array_name = (count($matches1)>0) ? $matches1[1][0] : $matches2[1][0];
							$array_key = (count($matches1)>0) ? $matches1[2][0] : $matches2[2][0];
							if(array_key_exists($array_name, $this->params) && is_array($this->params[$array_name])) {
								if(array_key_exists($array_key, $this->params[$array_name])) {
									return($this->parse($this->params[$array_name][$array_key]));
								} else {
									$this->parse_error = 'PARSING ERROR: Unknown key "'.$array_key.'" for array "'.$array_name.'" ';
									return(false);
								}
							} else {
								$this->parse_error = 'PARSING ERROR: Unknown array "'.$array_name.'" ';
								return(false);
							}
						} else {
							$re = '/^([A-Za-z][A-Za-z0-9_]*)\[(\d+)\]$/'; // arrays with alphanum keys
							if(preg_match($re, $f, $matches, PREG_OFFSET_CAPTURE, 0)){ 
								$array_name = $matches[1][0];
								$array_index = $matches[2][0];
								if(array_key_exists($array_name, $this->params) && is_array($this->params[$array_name])) {
									if(isset($this->params[$array_name][$array_index])) {
										return($this->parse($this->params[$array_name][$array_index]));
									} else {
										$this->parse_error = 'PARSING ERROR: Unknown index "'.$array_index.'" for array "'.$array_name.'" ';
										return(false);
									}
								} else {
									$this->parse_error = 'PARSING ERROR: Unknown array "'.$array_name.'" ';
									return(false);
								}
							} else {
								$re = '/^([A-Za-z][A-Za-z0-9_]*)$/'; // simple variables
								if(preg_match($re, $f, $matches, PREG_OFFSET_CAPTURE, 0)){ 
									if(array_key_exists($matches[0][0], $this->params)) return($this->parse($this->params[$matches[0][0]]));
									else {
										$this->parse_error = 'PARSING ERROR: Unknown expression "'.$matches[0][0].'" ';
										return(false);
									}
								} else {				
									$re = '/^\d*(\.\d+)?$/'; // unsigned float or int
									if(preg_match($re, $f, $matches, PREG_OFFSET_CAPTURE, 0)){ 
										return($matches[0][0]);
									} else {
										$this->parse_error = 'PARSING ERROR at '.$f.'<br/>';
										return(false);
									}
								}
							}
						}
					}
				}
			}
		}

	}

	private function comp($t) {
		if(!is_array($t)) return($t);
		if($t[0]===null) { // unary op or function
			if(in_array($t[1], $this->single_ops)) return($this->do_single_op($t[1],$this->comp($t[2])));
			if(in_array($t[1], $this->functions)) return($this->do_function($t[1],$this->comp($t[2])));
			return(0); // Should not happen really cause error catched during parsing...
		} else {
			return($this->do_dual_op($this->comp($t[0]), $t[1],$this->comp($t[2])));
		}
	}

	public function compute(){
		if($this->precision === null) return($this->comp($this->tree));
		else return(round($this->comp($this->tree),$this->precision, $this->precision_mode));
	}

}
