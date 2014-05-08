<?php
$modeList=Array("general","new","edit","view","list","search","find","standard");
$elementList=Array("table","ref_table","table_list","viewable","editable","chiave","campi_ordinamento","campi_obbligatori","print_form","data","button");
function print_arr($v){
    echo "<pre>";print_r($v);echo "</pre>";
}
function getControl($el){
    $attr=explode(';',$el);
    return $attr;
}
function getButton($el){
    $attr=explode(';',$el);
    return $attr;
}
function getElement($el,$type){
    switch($type){
            case "table":
            case "table_list":
            case "ref_table":
            case "print_form":
                $result=$el;
                break;
            case "chiave":
            case "viewable":
            case "editable":
            case "campi_obbligatori":
            case "campi_ordinamento":
                $result=explode(';',$el);
                break;
            case "button":
                $buttons=explode('|',$el);
                for($i=0;$i<count($buttons);$i++) $result[$i]=getButton($buttons[$i]);
                break;
            case "data":
                $ctrls=explode('|',$el);
                for($i=0;$i<count($ctrls);$i++) $result[$i]=getControl($ctrls[$i]);
                break;
            default:
                $result=$el;
                break;
    }
    return $result;
}
function getTab($f){
    $result=Array();
    $text=parse_ini_file($f,true);
    if (!is_array($text)) 
        return Array();
    $elements=Array();
    foreach($modeList as $mode){
        if (in_array($mode,array_keys($text))){
            $tab=$text[$mode];

            //$elements=array_merge($elements,$e);
            foreach($elementList as $el){
                if (in_array($el,array_keys($tab))){
                        $element=getElement($tab[$el],$el);
                }
            }
        }
            /*foreach($tab as $key=>$val){

            }*/
    }
    return $elements;
    return json_encode($result);
}
?>
<html>
<head>
	<script src="/js/jquery-1.10.2.js" language="javascript"></script>
	<script src="/js/jquery-ui-1.10.4.custom.min.js" language="javascript"></script>
	<link rel="stylesheet" href="/css/start/jquery-ui-1.10.4.custom.min.css"></link>

</head>
<body>
<?php
$project=($_REQUEST["project"])?($_REQUEST["project"]):("savona");
if (file_exists("$project.config.php"))
    require_once "$project.config.php";

$arrDirTab=Array("D:","Applicazioni","data",$project,"pe","praticaweb","tab");
$dir=implode(DIRECTORY_SEPARATOR,$arrDirTab).DIRECTORY_SEPARATOR;
$i=0;
$result=Array();
foreach(glob($dir."*") as $entry){

	if(filetype($entry)=='dir' && pathinfo($entry, PATHINFO_BASENAME)!='ce'){
		print "<h5>".pathinfo($entry, PATHINFO_BASENAME)."</h5>";
		print "<ol>";
		foreach(glob($entry.DIRECTORY_SEPARATOR."*") as $f){
			if(filetype($f)=='file' && pathinfo($f,PATHINFO_EXTENSION)=='tab'){
				$i++;
				print "<li>$f</li>";
				$r=getTab($f);
				if (is_array($r)) $result=array_unique(array_merge($result,$r));
				//print_arr($result);
				//if ($i==2) return;
			}
		}
		print "</ol>";
	}
}

//echo "<h5>Found $i tab file</he>"
print_arr($result);
?>
</body>
</html>
