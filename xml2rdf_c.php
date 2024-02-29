<?php
error_reporting(0);

$inputXML = file_get_contents('php://stdin');
$xmlfilename = "./xml/temp.xml".strtotime("now");
file_put_contents($xmlfilename, $inputXML);

$PD3xml = simplexml_load_file($xmlfilename);
$xml_file_string = file_get_contents($xmlfilename);


$pd3xmlData = array();
$mModified = $PD3xml -> attributes() -> modified;
$mDiagramName = $PD3xml -> diagram -> attributes() -> name;
$mDiagramID = $PD3xml -> diagram -> attributes() -> id;

//UserObjectタグの場合
foreach($PD3xml->diagram->mxGraphModel->root->UserObject as $UserObject):

		//変数初期化
 		$pd3layer="";
 		$pd3type="";
 		$pd3action ="";
 		$pd3action_status ="";
 		$arctype="";
 		$endArrow="";
 		$containertype="";
 		$pd3parent ="";
	    $pd3value = htmlspecialchars($UserObject['label']);
 		$pd3target="";
 		$pd3source="";
 		$pd3source="";
		$pd3id = $UserObject['id'];
		$entryX = "";
		$entryY="";
		$entryDx = "";
		$entryDy="";
		$exitX = "";
		$exitY="";
		$exitDx = "";
		$exitDy="";
		$errorcheck=0;
		$pd3tooltip=str_replace(array("\r\n", "\r", "\n"),"\\n",$UserObject['tooltip']);

	//UserObjectの場合
    foreach ($UserObject as $key => $mxCell) {

	if($mxCell['style']){
										$styleArray = array();			
										$styleArray = explode(";",$mxCell['style']);
										for($i = 0; $i < count($styleArray);$i++){
											if($styleArray[$i]){
												
											$stylePropertyArray = array();
											$stylePropertyArray = explode("=",$styleArray[$i]);
												$stylePeoperty[]=$stylePropertyArray[0];		
											if (preg_match("/URI/", $stylePropertyArray[0])){
																$mURI = str_replace("URI=","",$styleArray[$i]);
											}
											if (preg_match("/prefix/", $stylePropertyArray[0])){
																$mprefix = str_replace("prefix=","",$styleArray[$i]);
											}
											if (preg_match("/title/", $stylePropertyArray[0])){
																$mtitle = str_replace("title=","",$styleArray[$i]);
											}
											if (preg_match("/creator/", $stylePropertyArray[0])){
																$mcreator = str_replace("creator=","",$styleArray[$i]);
											}
											if (preg_match("/eptype/", $stylePropertyArray[0])){
																$eptype = str_replace("eptype=","",$styleArray[$i]);
											}
											if (preg_match("/description/", $stylePropertyArray[0])){
																$mdescription = str_replace("description=","",$styleArray[$i]);
											}
											if (preg_match("/pd3layer/", $stylePropertyArray[0])){
																$pd3layer = str_replace("pd3layer=","",$styleArray[$i]);
											}
											if (preg_match("/pd3type/", $stylePropertyArray[0])){
																$pd3type = str_replace("pd3type=","",$styleArray[$i]);
											}
											if (preg_match("/pd3action/", $stylePropertyArray[0])) {
																$pd3action = str_replace("pd3action=","",$styleArray[$i]);

																switch ($pd3action){
																			case 'ECDP':
																			    $pd3action_status = 'define problem';
  																				break;
																			case 'ECCAI':
																			    $pd3action_status = 'collect/analyze information';
  																				break;
																			case 'ECGH':
																			    $pd3action_status = 'generate hypothesis';
  																				break;
																			case 'ECESI':
																			    $pd3action_status = 'evaluate/select information';
  																				break;
																			case 'ECEX':
																			    $pd3action_status = 'execute';
  																				break;
  																}

											}
											if (preg_match("/arctype/", $stylePropertyArray[0])) {
																$arctype = str_replace("arctype=","",$styleArray[$i]);
											}		
											if (preg_match("/endArrow/", $stylePropertyArray[0])) {
																$endArrow = str_replace("endArrow=","",$styleArray[$i]);
											}											
											if (preg_match("/containertype/", $stylePropertyArray[0])) {
																$containertype = str_replace("containertype=","",$styleArray[$i]);
											}		
																						
											if (preg_match("/entryX/", $stylePropertyArray[0])) {
																$entryX = str_replace("entryX=","",$styleArray[$i]);
											}	
											
											if (preg_match("/entryY/", $stylePropertyArray[0])) {
																$entryY = str_replace("entryY=","",$styleArray[$i]);
											}
											
											if (preg_match("/entryDx/", $stylePropertyArray[0])) {
																$entryDx = str_replace("entryDx=","",$styleArray[$i]);
											}	
											
											if (preg_match("/entryDy/", $stylePropertyArray[0])) {
																$entryDy = str_replace("entryDy=","",$styleArray[$i]);
											}	
												
											if (preg_match("/exitX/", $stylePropertyArray[0])) {
																$exitX = str_replace("exitX=","",$styleArray[$i]);
											}	
											
											if (preg_match("/exitY/", $stylePropertyArray[0])) {
																$exitY = str_replace("exitY=","",$styleArray[$i]);
											}
											if (preg_match("/exitDx/", $stylePropertyArray[0])) {
																$exitDx = str_replace("exitDx=","",$styleArray[$i]);
											}	
											
											if (preg_match("/exitDy/", $stylePropertyArray[0])) {
																$exitDy = str_replace("exitDy=","",$styleArray[$i]);
											}	
											
											 }//if()
											} //for
																				
} //if_mxCell

	if($mxCell['parent']){$pd3parent = $mxCell['parent'];}																
	if($mxCell['source']){$pd3source = $mxCell['source'];	}																	
	if($mxCell['target']){$pd3target = $mxCell['target'];}


//mxGeometryを求める
	$CellText_start = strpos($xml_file_string," id=\"".$UserObject['id']."\"");
	$CellText_temp1 = substr($xml_file_string,$CellText_start);
	$CellText_end = strpos($CellText_temp1,"</mxCell>");
   $CellText_temp2 = substr($CellText_temp1,0,$CellText_end);

   if(substr_count($CellText_temp2,"Cell") > 1){
   		$CellText_end1 = strpos($CellText_temp2,"/>");
   		$CellText_temp2 = substr($CellText_temp2,0,$CellText_end1);
   }//mxCellが/>で終わる場合のチェック
 $mxGeometryText_start = strpos($CellText_temp2,"<mxGeometry");
 if($mxGeometryText_start !== false){
 	$mxGeometry= bin2hex(substr($CellText_temp2,$mxGeometryText_start));
 }else{$mxGeometry="";}

		
	$pd3type1="";
	$pd3type2="";
	
	//Actionの場合									
	if($pd3type=='action'){											
										if($pd3action_status){$pd3type2 = $pd3action_status;}
										elseif($value_text == 'Start'){$pd3type2 = "start";}
										elseif($value_text == 'End'){$pd3type2= "end";}
										else{$pd3type2= "nil";} 
										$pd3type1="pd3:Action";
										$pd3type2= "pd3:actionType \"".$pd3type2."\"";
										}	//Actionの場合_end
										
	//Containerの場合
	if($pd3type == 'container'){
										$pd3type1="pd3:Container";
										$pd3type2= "pd3:containerType \"".$containertype."\"";
									}//Containerの場合_end
	
	//arcの場合
	if($pd3type == 'arc'){
				if($arctype == 'hierarchization'){
					$pd3type1="pd3:ContainerFlow";
					$pd3type2= "pd3:arcType \"hierarchization\"";
				}elseif($arctype == 'tool/knowledge'){
					$pd3type1="pd3:SubstanceFlowFlow";
					$pd3type2= "pd3:arcType \"tool/knowledge\"";					
				}elseif($arctype == 'information'){
					$pd3type1="pd3:Flow";
					$pd3type2= "pd3:arcType \"information\"";					
				}elseif($arctype == 'annotation'){
					$pd3type1="pd3:ControlFlow";
					$pd3type2= "pd3:arcType \"annotation\"";		
				}elseif($arctype == 'rationale'){
					$pd3type1="pd3:ControlFlow";
					$pd3type2= "pd3:arcType \"rationale\"";
				}elseif($arctype == 'intention'){
					$pd3type1="pd3:ControlFlow";
					$pd3type2= "pd3:arcType \"intention\"";				
				}		
			}//arcの場合_end
			
//Documentの場合
	if($pd3type == 'document'){
										$pd3type1="pd3:Document";
									}//Documentの場合_end
//engineerの場合
	if($pd3type == 'engineer'){
										$pd3type1="pd3:Engineer";
									}//engineerの場合_end
									
//Toolの場合
	if($pd3type == 'tool'){
										$pd3type1="pd3:Tool";
									}//tool場合_end
												
	//pd3type1の情報がない場合
	if(!$pd3type1 && !$pd3type2 && $endArrow){
		
		if($endArrow == 'block'){
					$pd3type1="pd3:Flow";
					$pd3type2= "pd3:arcType \"information\"";		//矢印の場合はFlow
		}else{
					$pd3type1="pd3:Entity";		
		}
		
		$errorcheck=1;
	}
	
       
}
//UserObjectタグのデータをarrayに
 	$pd3xmlData[] = ['pd3id' => (string)$pd3id,'pd3type1' => (string)$pd3type1,'pd3type2' =>(string)$pd3type2 ,'pd3layer' => (string)$pd3layer,'pd3parent' => (string)$pd3parent,'pd3source' => (string)$pd3source,'pd3target' => (string)$pd3target,'pd3value' =>(string)$pd3value,'pd3tooltip' =>(string)$pd3tooltip,'mxGeometry' => $mxGeometry,'entryX'=>(string)$entryX,'entryY'=>(string)$entryY,'entryDx'=>(string)$entryDx,'entryDy'=>(string)$entryDy,'exitX'=>(string)$exitX,'exitY'=>(string)$exitY,'exitDx'=>(string)$exitDx,'exitDy'=>(string)$exitDy,'errorcheck'=>(string)$errorcheck];		
endforeach;

//objectタグの場合
foreach($PD3xml->diagram->mxGraphModel->root->object as $object):

 		$pd3layer="";
 		$pd3type="";
 		$pd3action ="";
 		$pd3action_status ="";
 		$arctype="";
 		$endArrow="";
 		$containertype="";
 		$pd3parent ="";
	    $pd3value = $object['label'];
 		$pd3target="";
 		$pd3source="";
 		$pd3source="";
		$pd3id = $object['id'];
		$entryX = "";
		$entryY="";
		$entryDx = "";
		$entryDy="";
		$exitX = "";
		$exitY="";
		$exitDx = "";
		$exitDy="";
		$errorcheck=0;
		$pd3tooltip=str_replace(array("\r\n", "\r", "\n"),"\\n",$object['tooltip']);

				
		//ここからobject/mxCellを処理する
    foreach ($object as $key => $mxCell) {

	if($mxCell['style']){
										$styleArray = array();			
										$styleArray = explode(";",$mxCell['style']);
										for($i = 0; $i < count($styleArray);$i++){
											if($styleArray[$i]){
												
											$stylePropertyArray = array();
											$stylePropertyArray = explode("=",$styleArray[$i]);
												$stylePeoperty[]=$stylePropertyArray[0];		
											if (preg_match("/URI/", $stylePropertyArray[0])){
																$mURI = str_replace("URI=","",$styleArray[$i]);
											}
											if (preg_match("/prefix/", $stylePropertyArray[0])){
																$mprefix = str_replace("prefix=","",$styleArray[$i]);
											}
											if (preg_match("/title/", $stylePropertyArray[0])){
																$mtitle = str_replace("title=","",$styleArray[$i]);
											}
											if (preg_match("/creator/", $stylePropertyArray[0])){
																$mcreator = str_replace("creator=","",$styleArray[$i]);
											}
											if (preg_match("/eptype/", $stylePropertyArray[0])){
																$eptype = str_replace("eptype=","",$styleArray[$i]);
											}
											if (preg_match("/description/", $stylePropertyArray[0])){
																$mdescription = str_replace("description=","",$styleArray[$i]);
											}
											if (preg_match("/pd3layer/", $stylePropertyArray[0])){
																$pd3layer = str_replace("pd3layer=","",$styleArray[$i]);
											}
											if (preg_match("/pd3type/", $stylePropertyArray[0])){
																$pd3type = str_replace("pd3type=","",$styleArray[$i]);
											}
											if (preg_match("/pd3action/", $stylePropertyArray[0])) {
																$pd3action = str_replace("pd3action=","",$styleArray[$i]);

																switch ($pd3action){
																			case 'ECDP':
																			    $pd3action_status = 'define problem';
  																				break;
																			case 'ECCAI':
																			    $pd3action_status = 'collect/analyze information';
  																				break;
																			case 'ECGH':
																			    $pd3action_status = 'generate hypothesis';
  																				break;
																			case 'ECESI':
																			    $pd3action_status = 'evaluate/select information';
  																				break;
																			case 'ECEX':
																			    $pd3action_status = 'execute';
  																				break;
  																}

											}
											if (preg_match("/arctype/", $stylePropertyArray[0])) {
																$arctype = str_replace("arctype=","",$styleArray[$i]);
											}		
											if (preg_match("/endArrow/", $stylePropertyArray[0])) {
																$endArrow = str_replace("endArrow=","",$styleArray[$i]);
											}											
											if (preg_match("/containertype/", $stylePropertyArray[0])) {
																$containertype = str_replace("containertype=","",$styleArray[$i]);
											}		
																						
											if (preg_match("/entryX/", $stylePropertyArray[0])) {
																$entryX = str_replace("entryX=","",$styleArray[$i]);
											}	
											
											if (preg_match("/entryY/", $stylePropertyArray[0])) {
																$entryY = str_replace("entryY=","",$styleArray[$i]);
											}
											
											if (preg_match("/entryDx/", $stylePropertyArray[0])) {
																$entryDx = str_replace("entryDx=","",$styleArray[$i]);
											}	
											
											if (preg_match("/entryDy/", $stylePropertyArray[0])) {
																$entryDy = str_replace("entryDy=","",$styleArray[$i]);
											}	
												
											if (preg_match("/exitX/", $stylePropertyArray[0])) {
																$exitX = str_replace("exitX=","",$styleArray[$i]);
											}	
											
											if (preg_match("/exitY/", $stylePropertyArray[0])) {
																$exitY = str_replace("exitY=","",$styleArray[$i]);
											}
											if (preg_match("/exitDx/", $stylePropertyArray[0])) {
																$exitDx = str_replace("exitDx=","",$styleArray[$i]);
											}	
											
											if (preg_match("/exitDy/", $stylePropertyArray[0])) {
																$exitDy = str_replace("exitDy=","",$styleArray[$i]);
											}	
											
											 }//if()
											} //for
																				
} //if_mxCell

	if($mxCell['parent']){$pd3parent = $mxCell['parent'];}																
	if($mxCell['source']){$pd3source = $mxCell['source'];	}																	
	if($mxCell['target']){$pd3target = $mxCell['target'];}


//mxGeometryを求める
	$CellText_start = strpos($xml_file_string," id=\"".$object['id']."\"");
	$CellText_temp1 = substr($xml_file_string,$CellText_start);
	$CellText_end = strpos($CellText_temp1,"</mxCell>");
   $CellText_temp2 = substr($CellText_temp1,0,$CellText_end);

   if(substr_count($CellText_temp2,"Cell") > 1){
   		$CellText_end1 = strpos($CellText_temp2,"/>");
   		$CellText_temp2 = substr($CellText_temp2,0,$CellText_end1);
   }//mxCellが/>で終わる場合のチェック
 $mxGeometryText_start = strpos($CellText_temp2,"<mxGeometry");
 if($mxGeometryText_start !== false){
 	$mxGeometry= bin2hex(substr($CellText_temp2,$mxGeometryText_start));
 }else{$mxGeometry="";}

		
	$pd3type1="";
	$pd3type2="";
	
	//Actionの場合									
	if($pd3type=='action'){											
										if($pd3action_status){$pd3type2 = $pd3action_status;}
										elseif($value_text == 'Start'){$pd3type2 = "start";}
										elseif($value_text == 'End'){$pd3type2= "end";}
										else{$pd3type2= "nil";} 
										$pd3type1="pd3:Action";
										$pd3type2= "pd3:actionType \"".$pd3type2."\"";
										}	//Actionの場合_end
										
	//Containerの場合
	if($pd3type == 'container'){
										$pd3type1="pd3:Container";
										$pd3type2= "pd3:containerType \"".$containertype."\"";
									}//Containerの場合_end
	
	//arcの場合
	if($pd3type == 'arc'){
				if($arctype == 'hierarchization'){
					$pd3type1="pd3:ContainerFlow";
					$pd3type2= "pd3:arcType \"hierarchization\"";
				}elseif($arctype == 'tool/knowledge'){
					$pd3type1="pd3:SubstanceFlowFlow";
					$pd3type2= "pd3:arcType \"tool/knowledge\"";					
				}elseif($arctype == 'information'){
					$pd3type1="pd3:Flow";
					$pd3type2= "pd3:arcType \"information\"";					
				}elseif($arctype == 'annotation'){
					$pd3type1="pd3:ControlFlow";
					$pd3type2= "pd3:arcType \"annotation\"";		
				}elseif($arctype == 'rationale'){
					$pd3type1="pd3:ControlFlow";
					$pd3type2= "pd3:arcType \"rationale\"";
				}elseif($arctype == 'intention'){
					$pd3type1="pd3:ControlFlow";
					$pd3type2= "pd3:arcType \"intention\"";				
				}		
			}//arcの場合_end
			
//Documentの場合
	if($pd3type == 'document'){
										$pd3type1="pd3:Document";
									}//Documentの場合_end
//engineerの場合
	if($pd3type == 'engineer'){
										$pd3type1="pd3:Engineer";
									}//engineerの場合_end
									
//Toolの場合
	if($pd3type == 'tool'){
										$pd3type1="pd3:Tool";
									}//tool場合_end
												
	//pd3type1の情報がない場合
	if(!$pd3type1 && !$pd3type2 && $endArrow){
		
		if($endArrow == 'block'){
					$pd3type1="pd3:Flow";
					$pd3type2= "pd3:arcType \"information\"";		//矢印の場合はFlow
		}else{
					$pd3type1="pd3:Entity";		
		}
		
		$errorcheck=1;
	}
	
       
}
//objectタグのデータをarrayに
 	$pd3xmlData[] = ['pd3id' => (string)$pd3id,'pd3type1' => (string)$pd3type1,'pd3type2' =>(string)$pd3type2 ,'pd3layer' => (string)$pd3layer,'pd3parent' => (string)$pd3parent,'pd3source' => (string)$pd3source,'pd3target' => (string)$pd3target,'pd3value' =>(string)$pd3value,'pd3tooltip' =>(string)$pd3tooltip,'mxGeometry' => $mxGeometry,'entryX'=>(string)$entryX,'entryY'=>(string)$entryY,'entryDx'=>(string)$entryDx,'entryDy'=>(string)$entryDy,'exitX'=>(string)$exitX,'exitY'=>(string)$exitY,'exitDx'=>(string)$exitDx,'exitDy'=>(string)$exitDy,'errorcheck'=>(string)$errorcheck];		
endforeach;


 //mxCellタグの場合
 foreach($PD3xml->diagram->mxGraphModel->root->mxCell as $mxCell):
 		
 		$pd3layer="";
 		$pd3type="";
 		$pd3action ="";
 		$pd3action_status ="";
 		$arctype="";
 		$endArrow="";
 		$containertype="";
 		$pd3parent ="";
	    $pd3value ="";
 		$pd3target="";
 		$pd3source="";
 		$pd3source="";
		$pd3id = $mxCell['id'];
		$entryX = "";
		$entryY="";
		$entryDx = "";
		$entryDy="";
		$exitX = "";
		$exitY="";
		$exitDx = "";
		$exitDy="";
		$errorcheck=0;
		$pd3tooltip="";

	if($mxCell['value']){
			$pd3value = mb_convert_kana($mxCell['value'],"a");
					}
			
	if($mxCell['style']){
										$styleArray = array();			
										$styleArray = explode(";",$mxCell['style']);
										for($i = 0; $i < count($styleArray);$i++){
											if($styleArray[$i]){
												
											$stylePropertyArray = array();
											$stylePropertyArray = explode("=",$styleArray[$i]);
												$stylePeoperty[]=$stylePropertyArray[0];		
											if (preg_match("/URI/", $stylePropertyArray[0])){
																$mURI = str_replace("URI=","",$styleArray[$i]);
											}
											if (preg_match("/prefix/", $stylePropertyArray[0])){
																$mprefix = str_replace("prefix=","",$styleArray[$i]);
											}
											if (preg_match("/title/", $stylePropertyArray[0])){
																$mtitle = str_replace("title=","",$styleArray[$i]);
											}
											if (preg_match("/creator/", $stylePropertyArray[0])){
																$mcreator = str_replace("creator=","",$styleArray[$i]);
											}
											if (preg_match("/eptype/", $stylePropertyArray[0])){
																$eptype = str_replace("eptype=","",$styleArray[$i]);
											}
											if (preg_match("/description/", $stylePropertyArray[0])){
																$mdescription = str_replace("description=","",$styleArray[$i]);
											}
											if (preg_match("/pd3layer/", $stylePropertyArray[0])){
																$pd3layer = str_replace("pd3layer=","",$styleArray[$i]);
											}
											if (preg_match("/pd3type/", $stylePropertyArray[0])){
																$pd3type = str_replace("pd3type=","",$styleArray[$i]);
											}
											if (preg_match("/pd3action/", $stylePropertyArray[0])) {
																$pd3action = str_replace("pd3action=","",$styleArray[$i]);

																switch ($pd3action){
																			case 'ECDP':
																			    $pd3action_status = 'define problem';
  																				break;
																			case 'ECCAI':
																			    $pd3action_status = 'collect/analyze information';
  																				break;
																			case 'ECGH':
																			    $pd3action_status = 'generate hypothesis';
  																				break;
																			case 'ECESI':
																			    $pd3action_status = 'evaluate/select information';
  																				break;
																			case 'ECEX':
																			    $pd3action_status = 'execute';
  																				break;
  																}

											}
											if (preg_match("/arctype/", $stylePropertyArray[0])) {
																$arctype = str_replace("arctype=","",$styleArray[$i]);
											}		
											if (preg_match("/endArrow/", $stylePropertyArray[0])) {
																$endArrow = str_replace("endArrow=","",$styleArray[$i]);
											}											
											if (preg_match("/containertype/", $stylePropertyArray[0])) {
																$containertype = str_replace("containertype=","",$styleArray[$i]);
											}		
																						
											if (preg_match("/entryX/", $stylePropertyArray[0])) {
																$entryX = str_replace("entryX=","",$styleArray[$i]);
											}	
											
											if (preg_match("/entryY/", $stylePropertyArray[0])) {
																$entryY = str_replace("entryY=","",$styleArray[$i]);
											}
											
											if (preg_match("/entryDx/", $stylePropertyArray[0])) {
																$entryDx = str_replace("entryDx=","",$styleArray[$i]);
											}	
											
											if (preg_match("/entryDy/", $stylePropertyArray[0])) {
																$entryDy = str_replace("entryDy=","",$styleArray[$i]);
											}	
												
											if (preg_match("/exitX/", $stylePropertyArray[0])) {
																$exitX = str_replace("exitX=","",$styleArray[$i]);
											}	
											
											if (preg_match("/exitY/", $stylePropertyArray[0])) {
																$exitY = str_replace("exitY=","",$styleArray[$i]);
											}
											if (preg_match("/exitDx/", $stylePropertyArray[0])) {
																$exitDx = str_replace("exitDx=","",$styleArray[$i]);
											}	
											
											if (preg_match("/exitDy/", $stylePropertyArray[0])) {
																$exitDy = str_replace("exitDy=","",$styleArray[$i]);
											}	
											
											 }//if()
											} //for
										
										
										
										
} //if_mxCell
										
	if($mxCell['parent']){$pd3parent = $mxCell['parent'];}																
	if($mxCell['source']){$pd3source = $mxCell['source'];	}																	
	if($mxCell['target']){$pd3target = $mxCell['target'];}


//mxGeometryを求める
	$CellText_start = strpos($xml_file_string,"<mxCell id=\"".$mxCell['id']."\"");
	$CellText_temp1 = substr($xml_file_string,$CellText_start);
	$CellText_end = strpos($CellText_temp1,"</mxCell>");
   $CellText_temp2 = substr($CellText_temp1,0,$CellText_end);

   if(substr_count($CellText_temp2,"Cell") > 1){
   		$CellText_end1 = strpos($CellText_temp2,"/>");
   		$CellText_temp2 = substr($CellText_temp2,0,$CellText_end1);
   }//mxCellが/>で終わる場合のチェック
 $mxGeometryText_start = strpos($CellText_temp2,"<mxGeometry");
 if($mxGeometryText_start !== false){
 	$mxGeometry= bin2hex(substr($CellText_temp2,$mxGeometryText_start));
 }else{$mxGeometry="";}

		
	$pd3type1="";
	$pd3type2="";
	
	//Actionの場合									
	if($pd3type=='action'){											
										if($pd3action_status){$pd3type2 = $pd3action_status;}
										elseif($value_text == 'Start'){$pd3type2 = "start";}
										elseif($value_text == 'End'){$pd3type2= "end";}
										else{$pd3type2= "nil";} 
										$pd3type1="pd3:Action";
										$pd3type2= "pd3:actionType \"".$pd3type2."\"";
										}	//Actionの場合_end
										
	//Containerの場合
	if($pd3type == 'container'){
										$pd3type1="pd3:Container";
										$pd3type2= "pd3:containerType \"".$containertype."\"";
									}//Containerの場合_end
	
	//arcの場合
	if($pd3type == 'arc'){
				if($arctype == 'hierarchization'){
					$pd3type1="pd3:ContainerFlow";
					$pd3type2= "pd3:arcType \"hierarchization\"";
				}elseif($arctype == 'tool/knowledge'){
					$pd3type1="pd3:SubstanceFlow";
					$pd3type2= "pd3:arcType \"tool/knowledge\"";					
				}elseif($arctype == 'information'){
					$pd3type1="pd3:Flow";
					$pd3type2= "pd3:arcType \"information\"";					
				}elseif($arctype == 'annotation'){
					$pd3type1="pd3:ControlFlow";
					$pd3type2= "pd3:arcType \"annotation\"";		
				}elseif($arctype == 'rationale'){
					$pd3type1="pd3:ControlFlow";
					$pd3type2= "pd3:arcType \"rationale\"";
				}elseif($arctype == 'intention'){
					$pd3type1="pd3:ControlFlow";
					$pd3type2= "pd3:arcType \"intention\"";				
				}		
			}//arcの場合_end
			
//Documentの場合
	if($pd3type == 'document'){
										$pd3type1="pd3:Document";
									}//Documentの場合_end
//engineerの場合
	if($pd3type == 'engineer'){
										$pd3type1="pd3:Engineer";
									}//engineerの場合_end							
//Toolの場合
	if($pd3type == 'tool'){
										$pd3type1="pd3:Tool";
									}//tool場合_end
//Knowledgeの場合
	if($pd3type == 'knowledge'){
										$pd3type1="pd3:Knowledge";
									}//tool場合_end
//Substanceの場合
	if($pd3type == 'substance'){
										$pd3type1="pd3:Substance";
									}//Substanceの場合_end
//Annotationの場合
	if($pd3type == 'annotation'){
										$pd3type1="pd3:Annotation";
									}//Annotationの場合_end							
//Intentionの場合
	if($pd3type == 'intention'){
										$pd3type1="pd3:Intention";
									}//tool場合_end
//Intentionの場合
	if($pd3type == 'rationale'){
										$pd3type1="pd3:Rationale";
									}//tool場合_end

//Knowledgeの場合
	if($pd3type == 'knowledge'){
										$pd3type1="pd3:Knowledge";
									}//tool場合_end


	//pd3type1の情報がない場合
	if(!$pd3type1 && !$pd3type2 && $endArrow){
		
		if($endArrow == 'block'){
					$pd3type1="pd3:Flow";
					$pd3type2= "pd3:arcType \"information\"";		//矢印の場合はFlow
		}else{
					$pd3type1="pd3:Entity";		
		}
		
		$errorcheck=1;
	}
	
//mxCellタグのデータをarrayに
 	$pd3xmlData[] = ['pd3id' => (string)$pd3id,'pd3type1' => (string)$pd3type1,'pd3type2' =>(string)$pd3type2 ,'pd3layer' => (string)$pd3layer,'pd3parent' => (string)$pd3parent,'pd3source' => (string)$pd3source,'pd3target' => (string)$pd3target,'pd3value' =>(string)$pd3value,'pd3tooltip' =>(string)$pd3tooltip,'mxGeometry' => $mxGeometry,'entryX'=>(string)$entryX,'entryY'=>(string)$entryY,'entryDx'=>(string)$entryDx,'entryDy'=>(string)$entryDy,'exitX'=>(string)$exitX,'exitY'=>(string)$exitY,'exitDx'=>(string)$exitDx,'exitDy'=>(string)$exitDy,'errorcheck'=>(string)$errorcheck];
 endforeach;
 
 //id順でソート
 $ids = array_column($pd3xmlData, 'pd3id');
array_multisort($ids, SORT_ASC, $pd3xmlData);
 
 //prefixなどの出力
 if(!$mURI){$mURI="http://digital-triplet.org/DT/".$mDiagramID;}
if(!$mprefix){$mprefix=$mDiagramID;}
$mprefix = "pd3".$mprefix;

$putTurtle = "@prefix ".$mprefix.": <".$mURI."> .\n";
$putTurtle .= "@prefix owl: <http://www.w3.org/2002/07/owl#> .\n";
$putTurtle .= "@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .\n";
$putTurtle .= "@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .\n";
$putTurtle .= "@prefix dcterms: <http://purl.org/dc/terms/> .\n";
$putTurtle .= "@prefix pd3: <http://DigitalTriplet.net/2021/08/ontology#> .\n\n";

 //EngineeringProcessの出力
$putTurtle .= $mprefix.":0 a pd3:EngineeringProcess;\n";
if($mDiagramName){$putTurtle .= "  pd3:diagramName \"".$mDiagramName."\";\n";}
if($mDiagramID){$putTurtle .= "  pd3:diagramId \"".$mDiagramID."\";\n";}
if($mtitle){$putTurtle .= "  dcterms:title \"".$mtitle."\";\n";}
if($mcreator){$putTurtle .= "  dcterms:creator \"".$mcreator."\";\n";}
if($mdescription){$putTurtle .= "  dcterms:description \"".$mdescription."\";\n";}
if($eptype){$putTurtle .= "  pd3:epType \"".$eptype."\";\n";}
$putTurtle .= "  dcterms:modified \"".$mModified."\";\n";
$putTurtle .= "  pd3:id \"0\".\n\n";


//以下arrayの内容を出力
foreach($pd3xmlData as $dunit){

$nosource=0; //errorcheck for source
$notarget=0; //errorcheck for target
$nolayer=0; //errorcheck for layer
				
if($dunit['pd3type1'] == '' && $dunit['pd3type2'] == '' && $dunit['value'] == '' && $dunit['mxGeometry'] == '' && $dunit['pd3parent'] != ''){


				$putTurtle .= $mprefix.":1 a pd3:Entity;\n";				
				$putTurtle .= "  pd3:isIncludedIn ".$mprefix.":0;\n";
				$putTurtle .= "  pd3:dparent ".$mprefix.":0;\n";
				$putTurtle .= "  pd3:id \"1\".\n\n";
}elseif($dunit['pd3type1'] != ''){
	
	if($dunit['pd3type1'] || $dunit['pd3value']){
		
		#sourceがcontainerなのか
		if($dunit['pd3source']){
		$sourceArray = array();
		$sourceArray =  array_column($pd3xmlData,'pd3id');
		$sourceResult = array_keys($sourceArray,$dunit['pd3source']);
		if($pd3xmlData[$sourceResult[0]]['pd3type1'] == 'pd3:Container'){
					$dunit['pd3type1'] = 'pd3:ContainerFlow';
					}
			}		
		
		#sourceがtool/engineer/knowledge/documentなのか
		if($dunit['pd3source']){
		$sourceArray = array();
		$sourceArray =  array_column($pd3xmlData,'pd3id');
		$sourceResult = array_keys($sourceArray,$dunit['pd3source']);
		if($pd3xmlData[$sourceResult[0]]['pd3type1'] == 'pd3:Engineer' || $pd3xmlData[$sourceResult[0]]['pd3type1'] == 'pd3:Knowledge' || $pd3xmlData[$sourceResult[0]]['pd3type1'] == 'pd3:Tool' || $pd3xmlData[$sourceResult[0]]['pd3type1'] == 'pd3:Document'){
					$dunit['pd3type2'] = "pd3:arcType \"".trim(str_replace('pd3:','',$pd3xmlData[$sourceResult[0]]['pd3type1']))."\"";
					$dunit['pd3type1'] = 'pd3:SubstanceFlow';
					}
			}	
		
		
		$putTurtle .= $mprefix.":".$dunit['pd3id']." a ".$dunit['pd3type1'].";\n";
		if($dunit['pd3type2']){$putTurtle .= "  ".$dunit['pd3type2'].";\n";}

		$putTurtle .= "  pd3:id \"".$dunit['pd3id']."\";\n";
		if($dunit['pd3layer']=='topic' || $dunit['pd3layer']=='phys' || $dunit['pd3layer']=='info'){$putTurtle .= "  pd3:layer \"".$dunit['pd3layer']."\";\n";}else{
				$nolayer = 1;
		}


		#containerの場合(member,contraction追加)
		if($dunit['pd3type1'] == 'pd3:Container'){
			
			$container_member_list="";
			$container_member_array = array();
			$container_member_array = array_column($pd3xmlData,'pd3parent');
			$container_member_result = array_keys($container_member_array,$dunit['pd3id']);
		
			if(count($container_member_result) > 0){
				$container_member_list .= "  pd3:member ";
				for($i=0;$i<count($container_member_result);$i++){
					$container_member_list .= $mprefix.":".$pd3xmlData[$container_member_result[$i]]['pd3id'];
					if($i<count($container_member_result)-1){$container_member_list .= ",";}
				}				
				$container_member_list .= ";\n";
				}		
		$putTurtle .= $container_member_list;			
		
		#altoutput,contraction追加	
		$altOutput_array = array();
		$altOutput_array = array_column($pd3xmlData,'pd3source');
		$altOutput_result = array_keys($altOutput_array,$dunit['pd3id']);
		if($pd3xmlData[$altOutput_result[0]]['pd3target']){
		$putTurtle .= "  pd3:contraction ".$mprefix.":".$pd3xmlData[$altOutput_result[0]]['pd3target'].";\n";
		}else{
							$putTurtle .= "#warning : please check pd3:contraction(arc : no target or no source) \n";}	
		}
	
	 	#parentがContainertypeの場合attribution追加
		if($dunit['pd3parent']){
		$attributionArray = array();
		$attributionArray =  array_column($pd3xmlData,'pd3id');
		$attributionResult = array_keys($attributionArray,$dunit['pd3parent']);
		if($pd3xmlData[$attributionResult[0]]['pd3type1'] == 'pd3:Container'){
			$putTurtle .= "  pd3:attribution ".$mprefix.":".$dunit['pd3parent'].";\n";
			}
		}
		
		
		
		#value、Flowがparentのvalue探し
		if($dunit['pd3value'] != ""){$putTurtle .= "  pd3:value \"\"\"".htmlspecialchars($dunit['pd3value'])."\"\"\";\n";}
		elseif(!$dunit['pd3value']  && ($dunit['pd3type1']=="pd3:Flow" ||  $dunit['pd3type1']=="pd3:ContainerFlow" || $dunit['pd3type1']=="pd3:SupFlow" || $dunit['pd3type1']=="pd3:ControlFlow" || $dunit['pd3type1']=="pd3:ControlFlow" || $dunit['pd3type1']=="pd3:ControlFlow"|| $dunit['pd3type1']=="pd3:SubstanceFlow")){
		$valueArray = array();
		$valuetext="";
		$valueArray =  array_column($pd3xmlData,'pd3parent');
		$valueResult = array_keys($valueArray,$dunit['pd3id']);
		$putTurtle .= "  pd3:value \"\"\"".htmlspecialchars(mb_convert_kana($pd3xmlData[$valueResult[0]]['pd3value'],"a"))."\"\"\";\n";
		}
		
		#parent追加
		if($dunit['pd3parent'] != ''){
					$putTurtle .= "  pd3:isIncludedIn ".$mprefix.":0;\n";
					$putTurtle .= "  pd3:dparent ".$mprefix.":".$dunit['pd3parent'].";\n";
		//parentが正しいかチェック
		//actionにflowinputがあるのか
		 		if($dunit['pd3type1'] == 'pd3:Action'){
			    $parentFlowCheckArray = array();
				$parentFlowCheckArray = array_column($pd3xmlData,'pd3target');
				$parentFlowCheckResult = array_keys($parentFlowCheckArray,$dunit['pd3id']);
				//flowinputがあるactionを発見
				if(count($parentFlowCheckResult) > 0){
					for($i=0;$i<count($parentFlowCheckResult);$i++){
						$parentFlowCheckformerActionid = $pd3xmlData[$parentFlowCheckResult[$i]]['pd3source'];
						//ここから一個前のobjectがactionかcontainerかを判断
										    $parentObjectCheckArray = array();
											$parentObjectCheckArray = array_column($pd3xmlData,'pd3id');
											$parentObjectCheckResult = array_keys($parentObjectCheckArray,$pd3xmlData[$parentFlowCheckResult[$i]]['pd3source']);
												if($pd3xmlData[$parentObjectCheckResult[0]]['pd3type1']=='pd3:Action' && $dunit['pd3parent'] != $pd3xmlData[$parentObjectCheckResult[0]]['pd3parent']){	
															$putTurtle .= "#Error :  diffrent parents :  (this) ".$dunit['pd3parent']." vs (previous) ".$pd3xmlData[$parentObjectCheckResult[0]]['pd3parent'].";\n";
												}

						}
					}
					
				}//actionチェック
		}//parent end
			
		#actionの場合
		if($dunit['pd3type1'] == 'pd3:Action'){
			#input探し
			$input_string="";
			$inputArray = array();
			$inputArray = array_column($pd3xmlData,'pd3target');
			$inputResult = array_keys($inputArray,$dunit['pd3id']);
			if(count($inputResult) > 0){
				$input_string .= "  pd3:input ";
				for($i=0;$i<count($inputResult);$i++){
					$formerActionid = $pd3xmlData[$inputResult[$i]]['pd3source']; 

					$formerActionArray = array_column($pd3xmlData,'pd3id');
					$formerActionResult = array_keys($formerActionArray,$pd3xmlData[$inputResult[$i]]['pd3source']);
					//以下ContainerInput追加
					if($pd3xmlData[$formerActionResult[0]]['pd3type1']=='pd3:Container'){
					$putTurtle .= "  pd3:expansion ".$mprefix.":".$pd3xmlData[$formerActionResult[0]]['pd3id'].";\n";
					//以下isSupportedBy追加
					}elseif($pd3xmlData[$formerActionResult[0]]['pd3type1']=='pd3:Tool' || $pd3xmlData[$formerActionResult[0]]['pd3type1']=='pd3:Engineer' || $pd3xmlData[$formerActionResult[0]]['pd3type1']=='pd3:Knowledge' || $pd3xmlData[$formerActionResult[0]]['pd3type1']=='pd3:Document' || $pd3xmlData[$formerActionResult[0]]['pd3type1']=='pd3:Substance'){
					if($pd3xmlData[$formerActionResult[0]]['pd3id']){	
					$putTurtle .= "  pd3:isSupportedBy ".$mprefix.":".$pd3xmlData[$formerActionResult[0]]['pd3id'].";\n";
					}
					//以下isControlledBy追加
					}elseif($pd3xmlData[$formerActionResult[0]]['pd3type1']=='pd3:Intention' || $pd3xmlData[$formerActionResult[0]]['pd3type1']=='pd3:Rationale' || $pd3xmlData[$formerActionResult[0]]['pd3type1']=='pd3:Annotation'){
					if($pd3xmlData[$formerActionResult[0]]['pd3id']){	
					$putTurtle .= "  pd3:isControlledBy ".$mprefix.":".$pd3xmlData[$formerActionResult[0]]['pd3id'].";\n";
					}
					}else{
						if(substr_count($input_string,':') > 1){
							$input_string .= ",".$mprefix.":".$pd3xmlData[$inputResult[$i]]['pd3id'];
						}else{
							$input_string .= $mprefix.":".$pd3xmlData[$inputResult[$i]]['pd3id'];						
						}
					}
				}				
				$input_string=$input_string.";\n";
				}				
		$putTurtle .= $input_string;
		
		#output探し
			$output_string="";
			$outputArray = array();
			$outputArray = array_column($pd3xmlData,'pd3source');
			$outputResult = array_keys($outputArray,$dunit['pd3id']);
			if(count($outputResult) > 0){
				$output_string .= "  pd3:output ";
				for($i=0;$i<count($outputResult);$i++){
						$output_string .= $mprefix.":".$pd3xmlData[$outputResult[$i]]['pd3id'];
					if($i<count($outputResult)-1){$output_string .= ",";}
				}				
				$output_string=$output_string.";\n";
				}					
		$putTurtle .= $output_string;	
		
		
		
		#altInput,
		}//actionのend
		
		#supportの場合
		if($dunit['pd3type1'] == 'pd3:Substance' || $dunit['pd3type1'] == 'pd3:Tool'  || $dunit['pd3type1'] == 'pd3:Document' || $dunit['pd3type1'] == 'pd3:Engineer'  || $dunit['pd3type1'] == 'pd3:Knowledge'){
			$support_string="";
			$supportArray = array();
			$supportArray = array_column($pd3xmlData,'pd3source');
			$supportResult = array_keys($supportArray,$dunit['pd3id']);
			if(count($supportResult) > 0){

					for($i=0;$i<count($supportResult);$i++){
						$formerSupportArray = array_column($pd3xmlData,'pd3id');
						$formerSupportResult = array_keys($formerSupportArray,$pd3xmlData[$supportResult[$i]]['pd3target']);			
						if($pd3xmlData[$formerSupportResult[0]]['pd3id']){
						$putTurtle .= "  pd3:support ".$mprefix.":".$pd3xmlData[$formerSupportResult[0]]['pd3id'].";\n";
						}
						
						if(!$pd3xmlData[$formerSupportResult[0]]['pd3id']){
							$putTurtle .= "#warning : please check pd3:support(arc : no target or no source) \n";}
					}
			}
		}//supportのend

		#controlの場合
		if($dunit['pd3type1'] == 'pd3:Annotation' || $dunit['pd3type1'] == 'pd3:Intention'  || $dunit['pd3type1'] == 'pd3:Rationale'){
			$control_string="";
			$controlArray = array();
			$controlArray = array_column($pd3xmlData,'pd3source');
			$controlResult = array_keys($controlArray,$dunit['pd3id']);
			if(count($controlResult) > 0){
					for($i=0;$i<count($controlResult);$i++){
						$formerControlArray = array_column($pd3xmlData,'pd3id');
						$formerControlResult = array_keys($formerControlArray,$pd3xmlData[$controlResult[$i]]['pd3target']);
						if($pd3xmlData[$formerControlResult[0]]['pd3id']){			
						$putTurtle .= "  pd3:control ".$mprefix.":".$pd3xmlData[$formerControlResult[0]]['pd3id'].";\n";
						}
						if(!$pd3xmlData[$formerControlResult[0]]['pd3id']){
							$putTurtle .= "#warning : please check pd3:control(arc : no target or no source) \n";}
					}
			}
		}//controlのend



		#error探し
		if($dunit['pd3type1']== 'pd3:Flow' || $dunit['pd3type1']== 'pd3:ContainerFlow' || $dunit['pd3type1']== 'pd3:SubstanceFlow'){
			if($dunit['pd3source']==''){$nosource=1;} //nosource?
			if($dunit['pd3target']==''){$notarget=1;} //notarget?
		}

		if($dunit['pd3type1']=="pd3:ControlFlow" || $dunit['pd3type1']=="pd3:ControlFlow" || $dunit['pd3type1']=="pd3:ControlFlow"){
					if($dunit['pd3target']==''){$notarget=1;} //notarget?
		}

		if($dunit['pd3source']){$putTurtle .= "  pd3:source ".$mprefix.":".trim($dunit['pd3source']).";\n";}
		if($dunit['pd3target']){$putTurtle .= "  pd3:target ".$mprefix.":".trim($dunit['pd3target']).";\n";}

		if($dunit['pd3tooltip']!=''){$putTurtle .= "  pd3:content \"".$dunit['pd3tooltip']."\";\n";}		
		if($dunit['entryX']!=''){$putTurtle .= "  pd3:entryX \"".$dunit['entryX']."\";\n";}
		if($dunit['entryY']!=''){$putTurtle .= "  pd3:entryY \"".$dunit['entryY']."\";\n";}
		if($dunit['entryDx']!=''){$putTurtle .= "  pd3:entryDx \"".$dunit['entryDx']."\";\n";}
		if($dunit['entryDy']!=''){$putTurtle .= "  pd3:entryDy \"".$dunit['entryDy']."\";\n";}
		if($dunit['exitX']!=''){$putTurtle .= "  pd3:exitX \"".$dunit['exitX']."\";\n";}
		if($dunit['exitY']!=''){$putTurtle .= "  pd3:exitY \"".$dunit['exitY']."\";\n";}
		if($dunit['exitDx']!=''){$putTurtle .= "  pd3:exitDx \"".$dunit['exitDx']."\";\n";}
		if($dunit['exitDy']!=''){$putTurtle .= "  pd3:exitDy \"".$dunit['exitDy']."\";\n";}
		if($dunit['mxGeometry']){$putTurtle .= "  pd3:geometry \"".$dunit['mxGeometry']."\".\n";}else{
			$putTurtle .= "  pd3:geometry \"\".\n";			
		}


	}
//if$dunit['pd3type1']のend


//errorの場合	
if($dunit['errorcheck']== '1'){
//$putTurtle .= "  # Error : Irregular XML syntax (no arctype, ".$dunit['pd3id'].") \n";
//$putTurtle .= "  # Warning : System​ inferred entity  value as ".$dunit['pd3type1'].". \n";
}	
if($nolayer== '1'){
$putTurtle .= "  # Error : Irregular XML syntax (no pd3layer, ".$dunit['pd3id'].") \n";
//$putTurtle .= "  # Warning : System​ inferred entity  value as ".$dunit['pd3type1'].". \n";
}	
if($notarget== '1'){
$putTurtle .= "  # Warning : no target in (".$dunit['pd3id'].") Flow \n"; 
}	

if($nosource== '1'){
$putTurtle .= "  # Warning : no source in  (".$dunit['pd3id'].") Flow\n"; 
}	


			$putTurtle .= "\n\n";
}

if(count($pd3xmlData)==0){
$putTurtle .= "  # Error : Irregular XML syntax \n";
}


	}//foreach end

	

$stdout= fopen( 'php://stdout', 'w' );
fwrite( $stdout, $putTurtle );
array_map('unlink', glob('ttl/*.*')); 
unlink($xmlfilename);
?>
