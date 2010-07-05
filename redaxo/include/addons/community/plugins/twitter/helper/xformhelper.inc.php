<?php

$oid = rex_request("oid","int");

//------------------------------
if($func == "add" || $func == "edit")
{
  
  if($func == "edit")
    echo '<div class="rex-area"><h3 class="rex-hl2">'.$bezeichner.' editieren</h3><div class="rex-area-content">';
  else
    echo '<div class="rex-area"><h3 class="rex-hl2">'.$bezeichner.' hinzuf�gen</h3><div class="rex-area-content">';
    
  // ***** Allgemeine BE Felder reinlegen
  $form_data  = "\n".'hidden|page|'.$page.'|REQUEST|no_db';
  $form_data .= "\n".'hidden|subpage|'.$subpage.'|REQUEST|no_db';
  $form_data .= "\n".'hidden|tripage|'.$tripage.'|REQUEST|no_db';
  $form_data .= "\n".'hidden|func|'.$func.'|REQUEST|no_db';

  $sc = new rex_sql();
  // $sc->debugsql = 1;
  $sc->setQuery('show columns from '.$table.'');
  
  foreach($sc->getArray() as $value)
  {
    switch($value["Field"])
    {

      // ****************** Validierungen

      // Pflichtfelder
      case("asdasdasd"):
        $form_data .= "\n".'validate|empty|'.$value["Field"].'|Bitte geben Sie im Feld "'.$value["Field"].'" etwas ein.';

      // Uniquefelder
      case("zhntgbrfv"):
        $form_data .= "\n".'validate|unique|'.$value["Field"].'|Der Wert im Feld "'.$value["Field"].'" existiert bereits.';
      

      // ****************** Feldtypen

      // date
      case("creatasdasde_date"):
        $form_data .= "\n".'datetime|'.$value["Field"].'|'.$value["Field"].'|';
        break;


      // select
      case("asdasd"):
      case("status"):
        $form_data .= "\n".'select|'.$value["Field"].'|'.$value["Field"].'|offline=0;online=1|';
        break;

      // select
      case("asdasd"):
        $form_data .= "\n".'select|'.$value["Field"].'|'.$value["Field"].'|0=0;1=1;2=2;3=3;4=4;5=5|';
        break;


      // checkbox / bool
      case("twitter"):
      case("event"):
        $form_data .= "\n".'checkbox|'.$value["Field"].'|'.$value["Field"];
        break;

      // textarea
      case("comment"):
      case("text"):
      case("teaser"):
      case("article"):
        $form_data .= "\n".'textarea|'.$value["Field"].'|'.$value["Field"];
        break;

      // leer
      case("id"):
        break;

      default:
        // sonstige
        $form_data .= "\n".'text|'.$value["Field"].'|'.$value["Field"];
        break;
        
        
    }
    
  }

  $form_data = trim(str_replace("<br />","",rex_xform::unhtmlentities($form_data)));

  $xform = new rex_xform;
  // $xform->setDebug(TRUE);
  $xform->objparams["actions"][] = array("type" => "showtext","elements" => array("action","showtext",'','<p>Vielen Dank f�r die Eintragung</p>',"",),);
  $xform->setObjectparams("main_table",$table); // f�r db speicherungen und unique abfragen

  if($func == "edit")
  {
    $form_data .= "\n".'hidden|oid|'.$oid.'|REQUEST|no_db';
    $xform->objparams["actions"][] = array("type" => "db","elements" => array("action","db",$table,"id=$oid"),);
    $xform->setObjectparams("main_id","$oid");
    $xform->setObjectparams("main_where","id=$oid");
    $xform->setGetdata(true); // Datein vorher auslesen
  }elseif($func == "add")
  {
    $xform->objparams["actions"][] = array("type" => "db","elements" => array("action","db",$table),);
  }

  $xform->setFormData($form_data);
  echo $xform->getForm();

  echo '</div></div>';
  
  echo '<br />&nbsp;<br /><table cellpadding="5" class="rex-table"><tr><td><a href="index.php?page='.$page.'&amp;subpage='.$subpage.'&amp;tripage='.$tripage.'"><b>&laquo; '.$I18N->msg('back_to_overview').'</b></a></td></tr></table>';
  
}






//------------------------------> Data l�schen
if($func == "delete"){
  $query = "delete from $table where id='".$oid."' ";
  $delsql = new rex_sql;
  $delsql->debugsql=0;
  $delsql->setQuery($query);
  $func = "";
  echo rex_info($bezeichner . " wurde gel&ouml;scht");
}











//------------------------------> Userliste
if($func == ""){

  /** Suche  **/
  $addsql = "";
  $link = "";
  
  $csuchtxt = rex_request("csuchtxt","string","");
  if($csuchtxt != ""){
    $link .= "&csuchtxt=".urlencode($csuchtxt);
  }
  
  $csuchfeld = rex_request("csuchfeld","array");
  $SUCHSEL = new rex_select();
  $SUCHSEL->setMultiple(1); 
  $SUCHSEL->setSize(5); 
  $SUCHSEL->setName("csuchfeld[]");
  $SUCHSEL->setStyle("width:100%;");

  $ssql   = new rex_sql();
  //$ssql->debugsql = 1;
  $ssql->setQuery('show columns from '.$table.'');

  for($i=0;$i<$ssql->getRows(); $i++){
    $SUCHSEL->addOption($ssql->getValue("Field"),$ssql->getValue("Field"));
    if(!is_array($csuchfeld))
      $SUCHSEL->setSelected($ssql->getValue("Field"));
    $ssql->next();
  }
  foreach($csuchfeld as $cs){
    $SUCHSEL->setSelected($cs);
    $link .= "&csuchfeld[]=".($cs);
  } 

  $suchform = '<table width=770 cellpadding=5 cellspacing=1 border=0 bgcolor=#ffffff class="rex-table">';
  $suchform .= '<form action="'.$_SERVER['PHP_SELF'].'" method="poost" >';
  $suchform .= '<input type="hidden" name="page" value="'.$page.'" />';
  $suchform .= '<input type="hidden" name="subpage" value="'.$subpage.'" />';
  $suchform .= '<input type="hidden" name="tripage" value="'.$tripage.'" />';
  $suchform .= '<input type="hidden" name="csuche" value="1" />';
  $suchform .= '<tr>
    <th>Suchbegriff</th>
    <th>Tabellenfelder �ber die gesucht wird</th>
    <th>&nbsp;</th>
    </tr>'; 
  $suchform .= '<tr>
    <td class="grey" valign="top"><input type="text" name="csuchtxt" value="'.htmlspecialchars(stripslashes($csuchtxt)).'" style="width:100%;" /></td>
    <td class="grey" valign="top">'.$SUCHSEL->get().'</td>
    <td class="grey" valign="top"><input type="submit" name="send" value="suchen"  class="inp100" /></td>
    </tr>';
  $suchform .= '</form>';
  $suchform .= '</table><br />';
  
  echo $suchform;
  
  $csuche = rex_request("csuche","int","0");
  
  if($csuche == 1)
  {
    if(is_array($csuchfeld) && count($csuchfeld)>0 && $csuchtxt != ""){
      $addsql .= "WHERE (";
      foreach($csuchfeld as $cs){
        $addsql .= " `".$cs."` LIKE  '%".$csuchtxt."%' OR ";      
      }
      $addsql = substr($addsql, 0, strlen($addsql)-3 );
      $addsql .= ")";
    } 
    $link .= "&csuche]".$csuche;
    
  }
  
  echo "<table cellpadding=5 class=rex-table><tr><td><a href=index.php?page=".$page."&subpage=".$subpage."&tripage=".$tripage."&func=add><b>+ $bezeichner anlegen</b></a></td></tr></table><br />";
  
  $sql = "select * from $table $addsql";

  $list = rex_list::factory($sql,30);
  $list->setColumnFormat('id', 'Id');

  // $list->setColumnParams("id", array("oid"=>"###id###","func"=>"edit"));
  // $list->setColumnParams("account", array("oid"=>"###id###","func"=>"edit"));
  $list->setColumnParams("editieren", array("oid"=>"###id###","func"=>"edit"));

  $list->addParam("page", $page);
  $list->addParam("subpage", $subpage);
  $list->addParam("tripage", $tripage);
  $list->addParam("csuchtxt", $csuchtxt);
  $list->addParam("csuche", $csuche );

  foreach($csuchfeld as $cs)
  {
    $list->addParam("csuchfeld[]", $cs);
  }

  foreach($gufa as $value)
  {
    $list->removeColumn($value);
  }

  $list->addColumn('editieren','editieren');
  $list->addColumn('l&ouml;schen','l&ouml;schen');
  $list->setColumnParams("l&ouml;schen", array("oid"=>"###id###","func"=>"delete"));
  
  /*
  $list->setColumnSortable('name');
  $list->addColumn('testhead','###id### - ###name###',-1);
  $list->addColumn('testhead2','testbody2');
  $list->setCaption('thomas macht das css');
  */
  
  echo $list->get();

}

?>