/*
 * DevEdit (CodePress) regular expressions for PHP, JavaScript, HTML & CSS syntax highlighting
 */
 
// JavaScript
Language.syntax = [ 
	{ input : /\"(.*?)(\"|<br>|<br\/>|<br \/>|<\/P>)/g, output : '<s>"$1$2</s>' }, // strings double quote
	{ input : /\'(.*?)(\'|<br>|<br\/>|<br \/>|<\/P>)/g, output : '<s>\'$1$2</s>' }, // strings single quote
	{ input : /(.*?){(.*?)}/g,output : '<b>$1{</b><u>$2</u><b>}</b>' }, // tags, ids, classes, values
//	{ input : /=(\#.*?) /g, output : '=<a>#$1</a> ' }, // html colors #
	{ input : /(&lt;a .*?&gt;|&lt;\/a&gt;)/g, output : '<a>$1</a>' }, // links
	{ input : /(&lt;img .*?&gt;)/g, output : '<big>$1</big>' }, // images
	{ input : /(&lt;\/?(button|textarea|form|input|select|option|label).*?&gt;)/g, output : '<u>$1</u>' }, // forms
	{ input : /(&lt;style.*?&gt;)(.*?)(&lt;\/style&gt;)/g, output : '<em>$1</em><em>$2</em><em>$3</em>' }, // style tags
	{ input : /(&lt;script.*?&gt;)(.*?)(&lt;\/script&gt;)/g, output : '<ins>$1</ins><ins>$2</ins><ins>$3</ins>' }, // script tags
//	{ input : /(&lt;style.*?&gt;)(.*?)(&lt;\/style&gt;)/g, output : '<em>$1</em>$2<em>$3</em>' }, // style tags
//	{ input : /(&lt;script.*?&gt;)(.*?)(&lt;\/script&gt;)/g, output : '<ins>$1</ins>$2<ins>$3</ins>' }, // script tags
	{ input : /(&lt;&lt;&lt;)(.*?)(<br>|<br\/>|<br \/>|<\/P>)/g, output : '$1<s>$2</s></cite>$3' }, // php raw text
	{ input : /(&lt;[^!\?]*?&gt;)/g, output : '<b>$1</b>' }, // all tags
	{ input : /(&lt;\?)/g, output : '<strong>$1' }, // <?.*
	{ input : /(\?&gt;)/g, output : '$1</strong>' }, // .*?>
	{ input : /(&lt;\?php|&lt;\?=|&lt;\?|\?&gt;)/g, output : '<cite>$1</cite>' }, // php tags
	{ input : /(\$[\w\.]*)/g, output : '<a>$1</a>' }, // vars
	{ input : /\b(false|true|and|or|xor|__FILE__|exception|__LINE__|array|as|break|case|class|const|continue|declare|default|die|do|echo|else|elseif|empty|enddeclare|endfor|endforeach|endif|endswitch|endwhile|eval|exit|extends|for|foreach|function|global|if|include|include_once|isset|list|new|print|require|require_once|return|static|switch|unset|use|while|__FUNCTION__|__CLASS__|__METHOD__|final|php_user_filter|interface|implements|extends|public|private|protected|abstract|clone|try|catch|throw|this|getenv|ereg|stripslashes|get_magic_quotes_gpc)\b/g, output : '<u>$1</u>' }, // PHP reserved words
	{ input : /\b(break|continue|do|for|new|this|void|case|default|else|function|return|typeof|while|if|label|switch|var|with|catch|boolean|int|try|false|throws|null|true|goto|gosub)\b/g, output : '<b>$1</b>' }, // SCRIPT reserved words
	{ input : /\b(alert|isNaN|parent|Array|parseFloat|parseInt|blur|clearTimeout|prompt|prototype|close|confirm|length|Date|location|Math|document|element|name|self|elements|setTimeout|navigator|status|String|escape|Number|submit|eval|Object|event|onblur|focus|onerror|onfocus|onclick|top|onload|toString|onunload|unescape|open|valueOf|window|onmouseover)\b/g, output : '<u>$1</u>' }, // SCRIPT special words
	{ input : /([^:]|^)\#(<br>|<br\/>|<br \/>|<\/P>)/g, output : '$1<i>#</i>$2' }, // comments #
	{ input : /([^:]|^)\#\ (.*?)(<br|<\/P)/g, output : '$1<i># $2</i>$3' }, // comments # 
	{ input : /([^:]|^)\#\-(.*?)(<br|<\/P)/g, output : '$1<i>#-$2</i>$3' }, // comments #-
	{ input : /([^:]|^)\/\/(.*?)(<br|<\/P)/g, output : '$1<i>//$2</i>$3' }, // comments //
	{ input : /\/\*(.*?)\*\//g, output : '<i>/*$1*/</i>' }, // comments /* */
	{ input : /(&lt;!--.*?--&gt.)/g, output : '<big>$1</big>' }, // html comments
	{ input : /(\$_SERVER[\w\.]*)/g, output : '<x>$1</x>' },
	{ input : /(\$_GET[\w\.]*)/g, output : '<x>$1</x>' },
	{ input : /(\$_POST[\w\.]*)/g, output : '<x>$1</x>' },
	{ input : /(\$_REQUEST[\w\.]*)/g, output : '<x>$1</x>' }
]

Language.snippets = [
	{ input : 'al', output : 'alert($0);' },
	{ input : 'all', output : 'document.all' },
	{ input : 'ar', output : 'array($0);' },
	{ input : 'array', output : 'array($0);' },
	{ input : 'br', output : 'break;' },
	{ input : 'case', output : 'case \'$0\' : break;' },
	{ input : 'cat', output : 'catch{\n\t$0\n}' },
	{ input : 'catch', output : 'catch{\n\t$0\n}' },
	{ input : 'con', output : 'confirm($0);' },
	{ input : 'confirm', output : 'confirm($0);' },
	{ input : 'co', output : 'continue;' },
	{ input : 'cont', output : 'continue;' },
	{ input : 'D', output : 'Date($0)' },
	{ input : 'da', output : 'document.all' },
	{ input : 'do', output : 'document.' },
	{ input : 'dw', output : 'document.write(\'$0\');' },
	{ input : 'getid', output : 'document.getElementById(\'$0\')' },
	{ input : 'gettags', output : 'getElementsByTagName(\'$0\')' },
	{ input : 'els', output : '}else{\n\t' },
	{ input : 'else', output : '}else{\n\t' },
	{ input : 'eli', output : '}else if($0){\n\t' },
	{ input : 'elif', output : '}else if($0){\n\t' },
	{ input : 'elseif', output : '}else if($0){\n\t' },
	{ input : 'ev', output : 'eval($0);' },
	{ input : 'eval', output : 'eval($0);' },
	{ input : 'F', output : 'false' },
	{ input : 'fa', output : 'false' },
	{ input : 'fo', output : 'for($0;;){\n\t\n}' },
	{ input : 'for', output : 'for($0;;){\n\t\n}' },
	{ input : 'fori', output : 'for(i=0;i<$0;i++){\n\t\n}' },
	{ input : 'forj', output : 'for(j=0;j<$0;j++){\n\t\n}' },
	{ input : 'fore', output : 'for($0 in ){\n\t\n}' },
	{ input : 'foreach', output : 'for($0 in ){\n\t\n}' },
	{ input : 'fu', output : 'function $0(){\n\t\n}' },	
	{ input : 'fun', output : 'function $0(){\n\t\n}' },	
	{ input : 'func', output : 'function $0(){\n\t\n}' },	
	{ input : 'funct', output : 'function $0(){\n\t\n}' },	
	{ input : 'function', output : 'function $0(){\n\t\n}' },	
	{ input : 'if', output : 'if($0){\n\t\n}' },
	{ input : 'ifb', output : 'if($0){ break; }' },
	{ input : 'ife', output : 'if($0){\n\t\n}else{\n\t\n}' },
	{ input : 'ifelse', output : 'if($0){\n\t\n}else{\n\t\n}' },
	{ input : 'in', output : 'indexOf($0,)' },
	{ input : 'ind', output : 'indexOf($0,)' },
	{ input : 'index', output : 'indexOf($0,)' },
	{ input : 'indexOf', output : 'indexOf($0,)' },
	{ input : 'la', output : 'lastIndexOf($0,)' },
	{ input : 'last', output : 'lastIndexOf($0,)' },
	{ input : 'lastIn', output : 'lastIndexOf($0,)' },
	{ input : 'lastIndex', output : 'lastIndexOf($0,)' },
	{ input : 'lastIndexOf', output : 'lastIndexOf($0,)' },
	{ input : 'le', output : 'length' },
	{ input : 'len', output : 'length' },
	{ input : 'lo', output : 'location.href = \'$0\';' },
	{ input : 'loc', output : 'location.href = \'$0\';' },
	{ input : 'M', output : 'Math.' },
	{ input : 'na', output : 'name' },
	{ input : 'nav', output : 'navigator' },
	{ input : 'pa', output : 'parse' },
	{ input : 'parI', output : 'parseInt()' },
	{ input : 'parF', output : 'parseFloat()' },
	{ input : 'par', output : 'parent' },
	{ input : 'pr', output : 'prompt(\'$0?\',\'_default_value_\');' },
	{ input : 'rep', output : 'replace(/$0/,\'\')' },
	{ input : 'repl', output : 'replace(/$0/,\'\')' },
	{ input : 'replace', output : 'replace(/$0/,\'\')' },
	{ input : 'ret0', output : 'return(0);' },
	{ input : 'retf', output : 'return(false);' },
	{ input : 'ret1', output : 'return(1);' },
	{ input : 'rett', output : 'return(true);' },
	{ input : 'ret', output : 'return($0);' },
	{ input : 'se', output : 'self.' },
	{ input : 'switch', output : 'switch($0) {\n\tcase "": break;\n\tdefault: ;\n}' },
	{ input : 'th', output : 'this.' },
	{ input : 'T', output : 'true' },
	{ input : 'tr', output : 'true' },
	{ input : 'try', output : 'try($0){\n\t\n}catch{\n\t\n}' },
	{ input : 'v', output : 'value' },
	{ input : 'va', output : 'var $0 = ;' },
	{ input : 'val', output : 'value' },
	{ input : 'var', output : 'var $0 = ;' },
	{ input : 'vo', output : 'void(0);' },
	{ input : 'void', output : 'void(0);' },
	{ input : 'while', output : 'while($0){\n\t\n}' },
	{ input : 'with', output : 'with($0){\n\t\n}' }
]

Language.complete = [
	{ input : '\'',output : '\'$0\'' },
	{ input : '"', output : '"$0"' },
	{ input : '(', output : '\($0\)' },
	{ input : '[', output : '\[$0\]' },
	{ input : '{', output : '{\n\t$0\n}' }		
]

Language.shortcuts = []
