<?php
error_reporting(0);

$ttl_file = file_get_contents("php://stdin");
$ttl_file = str_replace(array("\r\n", "\r", "\n"), "\n", $ttl_file);
$ttl_array = explode(".\n", $ttl_file);
$host="digital-triplet.net";
$prefix_data = array();

//arrow変換用
function makexml4arc($ttltext,$flowtype){
		$arc_array = array();
		$arc_array=explode(";\n",trim($ttltext));

			for($k=1;$k<count($arc_array);$k++){	
			if(preg_match("/pd3:id/", $arc_array[$k])){	
							$arc_id = trim(str_replace("pd3:id \"","",$arc_array[$k]));
							$arc_id = trim(str_replace("\"","",$arc_id));
				}elseif(preg_match("/pd3:layer/", $arc_array[$k])){	
							$arc_layer = trim(str_replace("pd3:layer \"","",$arc_array[$k]));
							$arc_layer = trim(str_replace("\"","",$arc_layer));
				}elseif(preg_match("/pd3:value/", $arc_array[$k])){	
							$arc_value = trim(str_replace("pd3:value \"","",$arc_array[$k]));
							$arc_value = trim(str_replace("\"","",$arc_value));
				}elseif(preg_match("/pd3:content/", $arc_array[$k])){	
							$arc_tooltip = trim(str_replace("pd3:content \"","",$arc_array[$k]));
							$arc_tooltip = trim(str_replace("\"","",$arc_tooltip));
							$arc_tooltip = trim(str_replace("\\n","&#xa;",$arc_tooltip));
				}elseif(preg_match("/pd3:dparent/", $arc_array[$k])){	
							$arc_parent = trim(str_replace("pd3:dparent ","",$arc_array[$k]));
							$arc_parent = trim(preg_replace('/.*?:/s', '', str_replace("\"","",$arc_parent)));
				}elseif(preg_match("/pd3:source/", $arc_array[$k])){	
							$arc_source = trim(str_replace("pd3:source ","",$arc_array[$k]));
							$arc_source = trim(preg_replace('/.*?:/s', '', str_replace("\"","",$arc_source)));							
				}elseif(preg_match("/pd3:target/", $arc_array[$k])){	
							$arc_target = trim(str_replace("pd3:target ","",$arc_array[$k]));
							$arc_target = trim(preg_replace('/.*?:/s', '', str_replace("\"","",$arc_target)));
				}elseif(preg_match("/pd3:entryX/", $arc_array[$k])){	
							$arc_entryX = trim(str_replace("pd3:entryX \"","",$arc_array[$k]));
							$arc_entryX = trim(str_replace("\"","",$arc_entryX));
				}elseif(preg_match("/pd3:entryY/", $arc_array[$k])){	
							$arc_entryY = trim(str_replace("pd3:entryY \"","",$arc_array[$k]));
							$arc_entryY = trim(str_replace("\"","",$arc_entryY));
				}elseif(preg_match("/pd3:entryDx/", $arc_array[$k])){	
							$arc_entryDx = trim(str_replace("pd3:entryDx \"","",$arc_array[$k]));
							$arc_entryDx = trim(str_replace("\"","",$arc_entryDx));
				}elseif(preg_match("/pd3:entryDy/", $arc_array[$k])){	
							$arc_entryDy = trim(str_replace("pd3:entryDy \"","",$arc_array[$k]));
							$arc_entryDy = trim(str_replace("\"","",$arc_entryDy));
				}elseif(preg_match("/pd3:exitX/", $arc_array[$k])){	
							$arc_exitX = trim(str_replace("pd3:exitX \"","",$arc_array[$k]));
							$arc_exitX = trim(str_replace("\"","",$arc_exitX));
				}elseif(preg_match("/pd3:exitY/", $arc_array[$k])){	
							$arc_exitY = trim(str_replace("pd3:exitY \"","",$arc_array[$k]));
							$arc_exitY = trim(str_replace("\"","",$arc_exitY));
				}elseif(preg_match("/pd3:exitDx/", $arc_array[$k])){	
							$arc_exitDx = trim(str_replace("pd3:exitDx \"","",$arc_array[$k]));
							$arc_exitDx = trim(str_replace("\"","",$arc_exitDx));
				}elseif(preg_match("/pd3:exitDy/", $arc_array[$k])){	
							$arc_exitDy = trim(str_replace("pd3:exitDy \"","",$arc_array[$k]));
							$arc_exitDy = trim(str_replace("\"","",$arc_exitDy));
				}elseif(preg_match("/pd3:geometry/", $arc_array[$k])){	
							$arc_geometry = trim(str_replace("pd3:geometry \"","",$arc_array[$k]));
							$arc_geometry = trim(str_replace("\"","",$arc_geometry));
				}
		}//for k	

		
		if($arc_layer == "topic"){$fillColor="#ffe6cc";
											  $strokeColor="#d79b00";
		}elseif($arc_layer == "info"){$fillColor="#dae8fc";
											  $strokeColor="#6c8ebf";
		}elseif($arc_layer == "phys"){$fillColor="#d5e8d4";
											  $strokeColor="#82b366";
		}
		if($flowtype == "information"){ $textalign="center";}else{$textalign="left";}
		if($flowtype == "annotation"){ $flowtype = $flowtype.";dashed=1";}		
		
		$xml_text = "";
		
		$style_text = "endArrow=block;rounded=0;endFill=1;html=1;align=".$textalign.";verticalAlign=middle;pd3layer=".$arc_layer.";pd3type=arc;arctype=".$flowtype.";fillColor=".$fillColor.";strokeColor=".$strokeColor.";fontSize=14;";
		if($arc_exitX != ''){$style_text.="exitX=".$arc_exitX.";";}	
		if($arc_exitY != ''){$style_text.="exitY=".$arc_exitY.";";}	
		if($arc_exitDx != ''){$style_text.="exitDx=".$arc_exitDx.";";}	
		if($arc_exitDy != ''){$style_text.="exitDy=".$arc_exitDy.";";}
		if($arc_entryX != ''){$style_text.="entryX=".$arc_entryX.";";}	
		if($arc_entryY != ''){$style_text.="entryY=".$arc_entryY.";";}	
		if($arc_entryDx != ''){$style_text.="entryDx=".$arc_entryDx.";";}	
		if($arc_entryDy != ''){$style_text.="entryDy=".$arc_entryDy.";";}	
		
		$xml_text = "<object label=\"".$arc_value."\" id=\"".$arc_id."\""; 
		if($arc_tooltip){$xml_text .= " tooltip=\"".$arc_tooltip."\"";}
		$xml_text .= ">\n";
		$xml_text .= "<mxCell style=\"".$style_text."\" edge=\"1\"";
		if($arc_parent){$xml_text .= " parent=\"".$arc_parent."\"";}
		if($arc_source){$xml_text .= " source=\"".$arc_source."\"";}
		if($arc_target){$xml_text .= " target=\"".$arc_target."\"";}
		$xml_text .= ">\n";
		if($arc_geometry){$xml_text .= hex2bin($arc_geometry);}
		$xml_text .= "</mxCell>\n";
		$xml_text .= "</object>\n";
	return $xml_text;
}

//object変換用
function makexml4object($ttltext,$objecttype){
		$object_array = array();
		$object_array=explode(";\n",trim($ttltext));

			for($k=1;$k<count($object_array);$k++){	
			if(preg_match("/pd3:id/", $object_array[$k])){	
							$object_id = trim(str_replace("pd3:id \"","",$object_array[$k]));
							$object_id = trim(str_replace("\"","",$object_id));
				}elseif(preg_match("/pd3:layer/", $object_array[$k])){	
							$object_layer = trim(str_replace("pd3:layer \"","",$object_array[$k]));
							$object_layer = trim(str_replace("\"","",$object_layer));
				}elseif(preg_match("/pd3:value/", $object_array[$k])){	
							$object_value = trim(str_replace("pd3:value \"","",$object_array[$k]));
							$object_value = trim(str_replace("\"","",$object_value));
				}elseif(preg_match("/pd3:content/", $object_array[$k])){	
							$object_tooltip = trim(str_replace("pd3:content \"","",$object_array[$k]));
							$object_tooltip = trim(str_replace("\"","",$object_tooltip));
							$object_tooltip = trim(str_replace("\\n","&#xa;",$object_tooltip));							
				}elseif(preg_match("/pd3:dparent/", $object_array[$k])){	
							$object_parent = trim(str_replace("pd3:dparent ","",$object_array[$k]));
							$object_parent = trim(preg_replace('/.*?:/s', '', str_replace("\"","",$object_parent)));
				}elseif(preg_match("/pd3:geometry/", $object_array[$k])){	
							$object_geometry = trim(str_replace("pd3:geometry \"","",$object_array[$k]));
							$object_geometry = trim(str_replace("\"","",$object_geometry));
				}
		}//for k	
		
		if($object_layer == "topic"){
														  $strokeColor="#d79b00";
		}elseif($object_layer == "info"){
												  		$strokeColor="#6c8ebf";
		}elseif($object_layer == "phys"){
											  			$strokeColor="#82b366";
		}
		
		$xml_text = "";
		if($objecttype == 'tool'){
			$style_text = "rounded=0;whiteSpace=wrap;html=1;pd3layer=".$object_layer.";pd3type=tool;strokeColor=".$strokeColor.";dashed=1;fontSize=14;fillColor=none;strokeWidth=1;";
		}elseif($objecttype == 'document'){
			$style_text = "rounded=0;whiteSpace=wrap;html=1;pd3layer=".$object_layer.";pd3type=document;strokeColor=".$strokeColor.";dashed=1;fontSize=14;fillColor=none;strokeWidth=1;";
		}elseif($objecttype == 'engineer'){
			$style_text="labelPosition=center;html=1;shape=mxgraph.basic.frame;dx=3;pd3layer=".$object_layer.";pd3type=engineer;strokeColor=".$strokeColor.";fontSize=14;fillColor=none;strokeWidth=1;";
		}
		
		$xml_text = "<object label=\"".$object_value."\" id=\"".$object_id."\"";
		if($object_tooltip){$xml_text .= " tooltip=\"".$object_tooltip."\"";}
		$xml_text .= ">\n";
		$xml_text .= "<mxCell style=\"".$style_text."\" vertex=\"1\"";
		if($object_parent){$xml_text .= " parent=\"".$object_parent."\"";}
		$xml_text .= ">\n";
		if($object_geometry){$xml_text .= hex2bin($object_geometry);}
		$xml_text .= "</mxCell>\n";
		$xml_text .= "</object>\n";
	return $xml_text;
}


for($i=0;$i<count($ttl_array);$i++){
	$ttl_array[$i] = trim($ttl_array[$i]);

	if(preg_match("/@prefix /", $ttl_array[$i])){
		//prefixの場合
		$prefix=explode("<",$ttl_array[$i]);
		$prefix_name_temp=str_replace("@prefix","",trim($prefix[0]));
		$prefix_name_temp=str_replace(":","",trim($prefix_name_temp));
		$uri_temp=str_replace(">","",trim($prefix[1]));		
		$prefix_data[]=['prefix_name' => (string)$prefix_name_temp,'prefix_uri' => (string)$uri_temp];
	}elseif(preg_match("/a pd3:EngineeringProcess/", $ttl_array[$i])){
		
	//EngineeringProcessの場合
		$ep_array = array();
		$ep_array=explode(";\n",trim($ttl_array[$i]));
		$prefix=trim(substr($ep_array[0], 0, strcspn($ep_array[0],':'))); //prefix確定
		for($j=1;$j<count($ep_array);$j++){
			$ep_array[$j]=substr(trim($ep_array[$j]),0,-1);
				
				if(preg_match("/pd3:diagramName/", $ep_array[$j])){
							$DiagramName = str_replace("pd3:diagramName \"","",$ep_array[$j]);
				}elseif(preg_match("/pd3:diagramId/", $ep_array[$j])){	
							$DiagramId = str_replace("pd3:diagramId \"","",$ep_array[$j]);
				}elseif(preg_match("/dcterms:title/", $ep_array[$j])){	
							$Title = str_replace("dcterms:title \"","",$ep_array[$j]);
				}elseif(preg_match("/dcterms:creator/", $ep_array[$j])){	
							$Creator = str_replace("dcterms:creator \"","",$ep_array[$j]);
				}elseif(preg_match("/dcterms:description/", $ep_array[$j])){	
							$Description = str_replace("dcterms:description \"","",$ep_array[$j]);
				}elseif(preg_match("/pd3:epType/", $ep_array[$j])){	
							$Eptype = str_replace("pd3:epType \"","",$ep_array[$j]);
				}elseif(preg_match("/dcterms:modified/", $ep_array[$j])){	
							$Modified = str_replace("dcterms:modified \"","",$ep_array[$j]);
				}elseif(preg_match("/pd3:id/", $ep_array[$j])){	
							$ep_id = trim(str_replace("pd3:id \"","",$ep_array[$j]));
							$ep_id = trim(str_replace("\"","",$ep_id));
				}
						
					}	
					
	}elseif(preg_match("/a pd3:Entity;/", $ttl_array[$i])){
		$entity_id = "";
		$entity_parent = "";
		$entity_array = array();
		$entity_array=explode(";\n",trim($ttl_array[$i]));
		for($k=1;$k<count($entity_array);$k++){	
			if(preg_match("/pd3:id/", $entity_array[$k])){
							$entity_id = trim(str_replace("pd3:id \"","",$entity_array[$k]));
							$entity_id = trim(str_replace("\"","",$entity_id));
							
			}
			if(preg_match("/pd3:dparent/", $entity_array[$k])){
							$entity_parent = trim(str_replace("pd3:dparent ","",$entity_array[$k]));
							$entity_parent = trim(preg_replace('/.*?:/s', '', str_replace("\"","",$entity_parent)));

			}
		}

		if($entity_id != '0'){
			$putXML_middle .= "<mxCell id=\"".$entity_id."\"";
				if($entity_parent != ''){
						$putXML_middle .= " parent=\"".$entity_parent."\"";
				}
		
		 	$putXML_middle .= " />\n"; 	
			}

	//Actionの場合
	}elseif(preg_match("/a pd3:Action;/", $ttl_array[$i])){
			$action_type="";
			$action_value="";
			$action_layer="";
			$action_tooltip="";
			$action_parent="";			
		$action_array = array();
		$action_array=explode(";\n",trim($ttl_array[$i]));
		for($k=1;$k<count($action_array);$k++){	
		

				if(preg_match("/pd3:actionType/", $action_array[$k])){
							$action_type = trim(str_replace("pd3:actionType \"","",$action_array[$k]));
							$action_type = trim(str_replace("\"","",$action_type));
				}elseif(preg_match("/pd3:id/", $action_array[$k])){	
							$action_id = trim(str_replace("pd3:id \"","",$action_array[$k]));
							$action_id = trim(str_replace("\"","",$action_id));
				}elseif(preg_match("/pd3:layer/", $action_array[$k])){	
							$action_layer = trim(str_replace("pd3:layer \"","",$action_array[$k]));
							$action_layer = trim(str_replace("\"","",$action_layer));
				}elseif(preg_match("/pd3:value/", $action_array[$k])){	
							$action_value = trim(str_replace("pd3:value \"","",$action_array[$k]));
							$action_value = trim(str_replace("\"","",$action_value));
				}elseif(preg_match("/pd3:content/", $action_array[$k])){	
							$action_tooltip = trim(str_replace("pd3:content \"","",$action_array[$k]));
							$action_tooltip = trim(str_replace("\"","",$action_tooltip));
							$action_tooltip = trim(str_replace("\\n","&#xa;",$action_tooltip));
				}elseif(preg_match("/pd3:dparent/", $action_array[$k])){	
							$action_parent = trim(str_replace("pd3:dparent \"","",$action_array[$k]));
							$action_parent = trim(preg_replace('/.*?:/s', '', str_replace("\"","",$action_parent)));
				}elseif(preg_match("/pd3:geometry/", $action_array[$k])){	
							$action_geometry = trim(str_replace("pd3:geometry \"","",$action_array[$k]));
							$action_geometry = trim(str_replace("\"","",$action_geometry));
				}
		}
				
		if($action_layer == "topic"){$fillColor="#ffe6cc";
											  $strokeColor="#d79b00";
		}elseif($action_layer == "info"){$fillColor="#dae8fc";
											  $strokeColor="#6c8ebf";
		}elseif($action_layer == "phys"){$fillColor="#d5e8d4";
											  $strokeColor="#82b366";
		}
		if($action_value == 'Start'){
					$dashed = "dashed=1;pd3action=start;";	
		}elseif($action_value == 'End'){
					$dashed = "dashed=1;pd3action=end;";
		}else{
			$dashed = "";
		} //start,endのstyle追加
		
				if($action_type == 'define problem'){
					$style_text="rounded=0;whiteSpace=wrap;html=1;fontSize=14;pd3layer=".$action_layer .";pd3type=action;pd3action=ECDP;strokeColor=".$strokeColor.";fontColor=#ffffff;fillColor=#D1BC35;";
				}elseif($action_type == 'collect/analyze information'){
					$style_text="rounded=0;whiteSpace=wrap;html=1;fontSize=14;pd3layer=".$action_layer .";pd3type=action;pd3action=ECCAI;strokeColor=".$strokeColor.";fontColor=#ffffff;fillColor=#3E54E6;";
				}elseif($action_type == 'generate hypothesis'){
					$style_text="rounded=0;whiteSpace=wrap;html=1;fontSize=14;pd3layer=".$action_layer .";pd3type=action;pd3action=ECGH;strokeColor=".$strokeColor.";fontColor=#ffffff;fillColor=#C93AC9;";
				}elseif($action_type == 'evaluate/select information'){
					$style_text="rounded=0;whiteSpace=wrap;html=1;fontSize=14;pd3layer=".$action_layer .";pd3type=action;pd3action=ECESI;strokeColor=".$strokeColor.";fontColor=#ffffff;fillColor=#8F4132;";
				}elseif($action_type == 'execute'){
					$style_text="rounded=0;whiteSpace=wrap;html=1;fontSize=14;pd3layer=".$action_layer .";pd3type=action;pd3action=ECEX;strokeColor=".$strokeColor.";fontColor=#ffffff;fillColor=#2EBAC9;";
				}else{
					$style_text="rounded=0;whiteSpace=wrap;html=1;pd3layer=".$action_layer .";pd3type=action;fillColor=".$fillColor.";strokeColor=".$strokeColor.";".$dashed;
				}					
								
				$putXML_middle .= "<object id=\"".$action_id."\"";
				if($action_value != ''){$putXML_middle .= " label=\"".$action_value."\"";}
				if($action_tooltip != ''){$putXML_middle .= " tooltip=\"".$action_tooltip."\"";}
				$putXML_middle .= ">\n<mxCell style=\"".$style_text."\" vertex=\"1\"";
				if($action_parent != ''){$putXML_middle .= " parent=\"".$action_parent."\"";}
				$putXML_middle .= " >";
				if($action_geometry){$putXML_middle .= hex2bin($action_geometry);}
				$putXML_middle .=  "</mxCell>\n</object>\n";
	//Actionの場合
	}elseif(preg_match("/a pd3:Container;/", $ttl_array[$i])){
		$container_array = array();
		$container_array=explode(";\n",trim($ttl_array[$i]));
		for($k=1;$k<count($container_array);$k++){	
		
			if(preg_match("/pd3:id/", $container_array[$k])){	
							$container_id = trim(str_replace("pd3:id \"","",$container_array[$k]));
							$container_id = trim(str_replace("\"","",$container_id));
				}elseif(preg_match("/pd3:layer/", $container_array[$k])){	
							$container_layer = trim(str_replace("pd3:layer \"","",$container_array[$k]));
							$container_layer = trim(str_replace("\"","",$container_layer));
				}elseif(preg_match("/pd3:value/", $container_array[$k])){	
							$container_value = trim(str_replace("pd3:value \"","",$container_array[$k]));
							$container_value = trim(str_replace("\"","",$container_value));
				}elseif(preg_match("/pd3:dparent/", $container_array[$k])){	
							$container_parent = trim(str_replace("pd3:dparent \"","",$container_array[$k]));
							$container_parent = trim(preg_replace('/.*?:/s', '', str_replace("\"","",$container_parent)));
				}elseif(preg_match("/pd3:geometry/", $container_array[$k])){	
							$container_geometry = trim(str_replace("pd3:geometry \"","",$container_array[$k]));
							$container_geometry = trim(str_replace("\"","",$container_geometry));
				}
		}
				
		if($container_layer == "topic"){$fillColor="#ffe6cc";
											  $strokeColor="#d79b00";
		}elseif($container_layer == "info"){$fillColor="#dae8fc";
											  $strokeColor="#6c8ebf";
		}elseif($container_layer == "phys"){$fillColor="#d5e8d4";
											  $strokeColor="#82b366";
		}
		$style_text="swimlane;pd3layer=phys;pd3type=container;containertype=specialization;fillColor=".$fillColor.";strokeColor=".$strokeColor.";startSize=23;fontSize=14;";
	
		
		$putXML_middle .= "<object id=\"".$container_id."\"";
			if($container_value != ''){$putXML_middle .= " label=\"".$container_value."\"";}
		$putXML_middle .= ">\n<mxCell style=\"".$style_text."\" vertex=\"1\"";
			if($container_parent != ''){$putXML_middle .= " parent=\"".$container_parent."\"";}
		$putXML_middle .= " >";
			if($container_geometry){$putXML_middle .= hex2bin($container_geometry);}
		$putXML_middle .=  "</mxCell>\n</object>\n";
	
		
		
		
	//Containerの場合
	}elseif(preg_match("/a pd3:Engineer;/", $ttl_array[$i])){
		$putXML_middle .= makexml4object($ttl_array[$i],"engineer");
	//Engineerの場合
	}elseif(preg_match("/a pd3:Document;/", $ttl_array[$i])){
		$putXML_middle .= makexml4object($ttl_array[$i],"document");
	//Knowledgeの場合
	}elseif(preg_match("/a pd3:Tool;/", $ttl_array[$i])){
		$putXML_middle .= makexml4object($ttl_array[$i],"tool");
	//Toolの場合
	}elseif(preg_match("/a pd3:Flow/", $ttl_array[$i])){
		$putXML_middle .= makexml4arc($ttl_array[$i],"information");
		//Flowの場合
	}elseif(preg_match("/a pd3:SubjectFlow;/", $ttl_array[$i])){
		$putXML_middle .= makexml4arc($ttl_array[$i],"tool/knowledge");
		//ToolFlowの場合
	}elseif(preg_match("/a pd3:RationaleFlow;/", $ttl_array[$i])){
		$putXML_middle .= makexml4arc($ttl_array[$i],"rationale");
		//RationaleFlowの場合
	}elseif(preg_match("/a pd3:AnnotationFlow;/", $ttl_array[$i])){
		$putXML_middle .= makexml4arc($ttl_array[$i],"annotation");
		//AnnotationFlowの場合
	}elseif(preg_match("/a pd3:IntentionFlow;/", $ttl_array[$i])){
		$putXML_middle .= makexml4arc($ttl_array[$i],"intention");
		//IntentionFlowの場合
	}elseif(preg_match("/a pd3:ContainerFlow;/", $ttl_array[$i])){
		$putXML_middle .= makexml4arc($ttl_array[$i],"hierarchization");		
		//ContainerFlowの場合
	}

}//for_ttl_array


//prefixのuri探し
$prefix_search_array = array_column($prefix_data,'prefix_name');
$prefix_search_array_result = array_keys($prefix_search_array,$prefix);
$prefix_uri=$prefix_data[$prefix_search_array_result[0]]['prefix_uri'];
//var_dump($prefix_search_array_result);
//$formerActionResult = array_keys($formerActionArray,$pd3xmlData[$inputResult[$i]]['pd3source']);

$putXML_top="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$putXML_top .= "<mxfile host=\"".$host."\" modified=\"".$Modified."\" >\n";
$putXML_top .= "<diagram id=\"".$DiagramId."\" name=\"".$DiagramName."\">\n";
$putXML_top .= " <mxGraphModel>\n";
$putXML_top .= "<root>\n";
$putXML_top .= "<mxCell id=\"".$ep_id."\" style=\"URI=".$prefix_uri.";prefix=".$prefix.";title=".$Title.";creator=".$Creator.";description=".$Description.";eptype=".$Eptype.";\" />\n";
$putXML_bottom = "</root>\n</mxGraphModel>\n</diagram>\n</mxfile>";
$putXML = $putXML_top.$putXML_middle.$putXML_bottom;



$stdout= fopen( 'php://stdout', 'w' );
fwrite( $stdout, $putXML );
?>