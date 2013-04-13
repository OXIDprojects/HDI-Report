<?php
/**
* HDIReport
* for Oxid eShop 4.5.0
*
*  This program is free software: you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation, either version 3 of the License, or
*  (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*  @author HEINER DIRECT GmbH & Co.KG
*  @author Rafael Dabrowski
*  @link http://www-heiner-direct.com
*
*  @copyright HEINER DIRECT GmbH & Co. KG 2011
*  @license GPLv3
*
*/

class hdi_report extends oxAdminView
{
	//Definition der ZEntralen Daten
	//TemplateFile
	protected $_sThisTemplate = "hdiReport_main.tpl";
	public $_sChart = '';
	protected $_oOrderList = null;
	protected $_aFields = array();
	protected $_avg = 0;
	protected $stdRef = "4005001";
	protected $hasMarkCodes = false;

	//Render Funktion wird bei jedem Seitenaufruf aufgerufen
	public function render()
	{
//      ini_set('display_errors', true);
		parent::render();

		setlocale(LC_TIME, "de_DE");
		//Auslesen/definieren der Formular Werte
		$this->startdate = (oxConfig::getParameter("startdate") != "")? oxConfig::getParameter("startdate"): date("Y-m-")."01";
		$this->enddate = (oxConfig::getParameter("enddate") != "")? oxConfig::getParameter("enddate"): date("Y-m-d");
		$this->art = (oxConfig::getParameter("art") != "")? oxConfig::getParameter("art"): "UmsatzDatum";
		$this->chart = (oxConfig::getParameter("chart") != "")? oxConfig::getParameter("chart"): "column";
		$this->catfilter = (oxConfig::getParameter("catfilter") != "")? oxConfig::getParameter("catfilter") :"0";
		$this->markfilter = (oxConfig::getParameter("markfilter") != "ohne")? oxConfig::getParameter("markfilter") :"ohne";
		$this->prodfilter = (oxConfig::getParameter("prodfilter") != "")? oxConfig::getParameter("prodfilter") :"";
		$this->groupvars = oxConfig::getParameter("groupvars");
		$this->sort = oxConfig::getParameter("sort");
		$this->netto = oxConfig::getParameter("netto");
		$this->tpl = oxConfig::getParameter("tpl");
		$this->fav = oxConfig::getParameter("fav");
		$this->maxval = $this->toFloat(oxConfig::getParameter("maxumsatz"));
		$this->minval = $this->toFloat(oxConfig::getParameter("minumsatz"));
		$this->limit = intval(oxConfig::getParameter("limit"));

		
		//Bereitstellen der Informationen Für das Template
		$oSmarty = oxUtilsView::getInstance()->getSmarty();
		$oSmarty->assign( "oViewConf", $this->_aViewData["oViewConf"]);
		$oSmarty->assign( "chart", $this->chart);
		$oSmarty->assign( "date", date("Ymdhis"));
		$oSmarty->assign( "startdate", $this->startdate);
		$oSmarty->assign( "enddate", $this->enddate);
		$oSmarty->assign( "charts", array($this->chart => "checked"));
		$oSmarty->assign( "arts", array($this->art => "checked"));
		$oSmarty->assign( "catfilter", array($this->catfilter => "selected"));
		$oSmarty->assign( "groupvars", $this->groupvars);
		$oSmarty->assign( "prodfilter", $this->prodfilter);
		$oSmarty->assign( "sort", $this->sort);
		$oSmarty->assign( "netto", $this->netto);
		$oSmarty->assign( "hasMarketing", $this->hasMarkCodes);
		$oSmarty->assign( "elements", $this->_elem);
		$oSmarty->assign( "isjson", $this->tpl);
		if($this->_total >0) $oSmarty->assign( "avg", round($this->_total/$this->_elem, 2));
		$oSmarty->assign( "height", ($this->chart == "column")?200+$this->_elem*15:"100%");

		if($this->fav != "")
		{
			$oSmarty->assign( "json", $this->save_conf($this->fav) );
		}else
		{		
			$oSmarty->assign( "json", $this->getJSObject());
		}
		
		return $this->_sThisTemplate;
	}

	protected function toFloat($val)
	{
		$val = str_replace(".","",$val);
		$val = str_replace(",",".",$val);
		$val = floatval($val);
		return $val;	
	}
	
	public function save_conf($val)
	{
		$config = oxConfig::getInstance();
		$config->saveShopConfVar("string", "hdiReport", $val); 	
		return "OK";
	}
	
	public function load_conf()
	{
		$config = oxConfig::getInstance();
		if($config->getShopConfVar("hdiReport"))
		{
			return  $config->getShopConfVar("hdiReport");
		}
		return "[]";
	}
	
	//Erstellt den SQL Query und führt Ihn aus
	//return: Array[][]
	protected function getQuery()
	{
		$oLang = oxLang::getInstance();
		$aQuery = array();
		//Hilfs Array für jede Reportart
		//Form: key = Reportart = array ("FELDER die Selectiert werden: [0] = Beschriftung X achse;[1] = Wert; [2]= Beschreibung; [[3]= Stückzahl; [4]= Parentid; ]", "Group By Feld");
		$aQuery["UmsatzProdukt"] = array("articles.artnum, SUM(articles.netprice) umsatz, concat(articles.title,' '".(($this->groupvars!="checked")?", articles.varselect":"").", '-\n".$oLang->translateString('HDIREPORT_SOLDUNITS').": ', Sum(articles.amount), '\n".$oLang->translateString('HDIREPORT_ORDERS').":',count(distinct oxorder.oxid))",($this->groupvars!="checked")?"articles.oxid":"articles.parentid" );
		$aQuery["UmsatzDatum"] = array("date(oxorder.oxorderdate), SUM(oxorder.oxtotalordersum/(Select Count(*) From oxorderarticles where oxorder.oxid = oxorderarticles.oxorderid)) umsatz, concat(date(oxorder.oxorderdate), '-\n".$oLang->translateString('HDIREPORT_SOLDUNITS').": ', Sum(articles.amount), '\n".$oLang->translateString('HDIREPORT_ORDERS').": ',count(distinct oxorder.oxid))","date(oxorder.oxorderdate)");
		$aQuery["UmsatzMonat"] = array("Concat(Month(oxorder.oxorderdate),' ', Year(oxorder.oxorderdate)), SUM(oxorder.oxtotalordersum/(Select Count(*) From oxorderarticles where oxorder.oxid = oxorderarticles.oxorderid)) umsatz, concat('\nVerkaufte Produkte: ', Sum(articles.amount), '\n".$oLang->translateString('HDIREPORT_ORDERS').": ',count(distinct oxorder.oxid))","Year(oxorder.oxorderdate), MONTH(oxorder.oxorderdate)");
		$aQuery["UmsatzMarketingCode"] = array("oxorder.oxkeycode, SUM(oxorder.oxtotalordersum/(Select Count(*) From oxorderarticles where oxorder.oxid = oxorderarticles.oxorderid)) umsatz, concat(oxorder.oxkeycode,'\n".$oLang->translateString('HDIREPORT_SOLDUNITS').": ',Sum(articles.amount), '\n".$oLang->translateString('HDIREPORT_ORDERS').": ', count(distinct oxorder.oxid))","oxorder.oxkeycode");

		//Der SQL QUERY mit Platzhaltern für Bestimte Query Elemente.
		$sQuery = "	SELECT ".$aQuery[$this->art][0].", count(distinct oxorder.oxid) bestellungen, sum(articles.amount) produkte, articles.parentid
					FROM oxorder 
						INNER JOIN 
						( 
							SELECT oxorderarticles.oxartid oxid, oxorderarticles.OXTITLE title, oxorderarticles.OXAMOUNT amount, oxorderarticles.oxartnum artnum, oxorderarticles.ox".(($this->netto == "checked")?"brut":"net")."price netprice, oxarticles.oxvarselect varselect, oxorderarticles.oxorderid orderid, CASE oxarticles.oxparentid WHEN '' THEN oxorderarticles.oxartid ELSE oxarticles.oxparentid END parentid
							FROM oxorderarticles 
							LEFT OUTER JOIN oxarticles 
							ON oxorderarticles.OXARTID = oxarticles.OXID
						) articles
					ON articles.orderid = oxorder.OXID
					".
		$this->getWhereQuery()."
					GROUP BY ".$aQuery[$this->art][1].$this->valueFilterQuery().
		$this->getSortQuery().$this->getLimit().";";
		//Datenbank Abfrage
		$oDB = oxDb::getDb();
		//echo $sQuery."\n\n\n";
		$rs =  $oDB->execute( $sQuery);
		//print_r($rs);
		return $rs;
	}

	protected function getWhereQuery()
	{
		return " WHERE oxorder.OXORDERDATE BETWEEN '$this->startdate 00:00' AND '$this->enddate 23:59'".$this->catFilterQuery().$this->prodFilterQuery().$this->markFilterQuery();
	}
	
	protected function getSortQuery()
	{
		
		$direction = "DESC";
		if( oxConfig::getParameter("aufsteigend"))
		{
			$direction = "ASC";	
		
		}
		$query = " ORDER BY oxorder.oxorderdate ".$direction; 
		switch($this->sort)
		{
			case "Umsatz":
				$query = " ORDER BY umsatz ".$direction; 
				break; 
			case "Produkten": 
				$query = " ORDER BY produkte ".$direction; 
				break; 
			case "Bestellungen":
				$query = " ORDER BY bestellungen ".$direction;
				break;
		}
		return $query;;
	}
	

	//Funktion bestimmt den WHERE Query für das Filtern nach Kategorien
	protected function catFilterQuery()
	{

		return "";
	}

	//Funktion bestimmt den WHERE Query für das filtern nach dem Marketingcode
	protected function markFilterQuery()
	{
		if($this->markfilter != "ohne"&& $this->hasMarkCodes)
		{
			return " AND oxorder.oxkeycode = '$this->markfilter'";
		}
		return "";
	}

	protected function valueFilterQuery()
	{
		$query = "";
		if($this->minval > 0 or $this->maxval > 0)
		 {
		  $query =" HAVING ";
		 }
		if($this->minval > 0)
		{
			$query .= "umsatz >= ".$this->minval;
		}
				if($this->minval > 0 and $this->maxval > 0)
		 {
		  $query .=" AND ";
		 }
		if($this->maxval > 0)
		{
			$query .= "umsatz <= ".$this->maxval;
		}
		return $query;
	}
	
	//Funktion bestimmt den WHERE Query für das Filtern nach bestimmten Produkten
	protected function prodFilterQuery()
	{
		$query = "";
		if($this->prodfilter != "")
		{
			$prods = explode(";", $this->prodfilter);
			$query = " AND (";
			foreach($prods as $key => $prod)
			{
				$query .= "articles.artnum LIKE '".mysql_real_escape_string($prod)."%'"   ;
				if(count($prods)-1 != $key)
				{
					$query .= " OR ";
				}
			}
			$query .= ")";
		}
		return $query;
	}
	
	protected function getLimit()
	{
		$query = ""; 
		if($this->limit > 0)
		{
				$query = " LIMIT ".$this->limit;
		}
		return $query;
	
	}
	
	public function getJSObject()
	{
		$arr = array();
		$table= $this->getQuery();
		if(is_object($table))
		{
			$a = $table->GetArray();
			$this->cleanDataArray($a);
			$avg = 0;
			$i=0;
			foreach($a as $oOrder)
			{
				$oJS = new stdClass;
				$oJS->title = $oOrder[0];
				$oJS->value = $oOrder[1];
				$oJS->description =  $oOrder[2];
				$oJS->order = $oOrder[3];
				$oJS->sold = $oOrder[4];
				$oJS->month = $oOrder["month"];
				$arr[$i] = $oJS;
				$i++;
			}
		}
		return json_encode($arr);
	}

	protected function cleanString($string)
	{
		return str_replace("&amp;"," ",str_replace("&"," ",str_replace("'"," ", $string)));
	}

	public function getCategoryForm()
	{
		$form = '<select name="catfilter"><option value="0">nicht Filtern</option>';
		$oDB = oxDb::getDb();
		$rs =  $oDB->execute( "SELECT DISTINCT oxtitle FROM oxcategories WHERE oxparentid = 'oxrootid'");
		if(is_object($rs))
		{
			$a = $rs->GetArray();
			$i = 1;
			foreach($a as $code)
			{
				$form .= "<option value=\"".$i."\" ".(($i == $this->markfilter)? "selected":"")." >$code[0]</option>";
				$i++;
			}
		}
		$form .="</select>";
		return $form;
	}

	protected function cleanDataArray(&$array)
	{
		if($this->groupvars == "checked" && $this->art == "UmsatzProdukt")
		{
			$b= array();
			$i = 0; 
			foreach($array as $item)
			{
				$tmp = explode("-", $item[0]);
				$b[$i] = $item;
				$b[$i][0]= (is_array($tmp))? $tmp[0]: $tmp;
				$i++;
			}
			$array = $b;
		}
		if($this->art == "UmsatzDatum")
		{
			$b= array();
			$oLang = oxLang::getInstance();
			$month = json_decode($oLang->translateString("HDIREPORT_MONTHVALUES"));
			
			foreach($array as $item)
			{
				$tmp = explode("-", $item[2]);
				$b[$item[0]] = $item;
				$b[$item[0]][0] = $tmp[2].".".$tmp[1].".".$tmp[0];
				$b[$item[0]][2] = utf8_encode(strftime('%A, %d. %B %Y',strtotime($b[$item[0]][0])).$tmp[3]);
			}
			$array = $b;
		}
		if($this->art == "UmsatzMonat")
		{
			$b= array();	
			$oLang = oxLang::getInstance();
			$month = json_decode($oLang->translateString("HDIREPORT_MONTHVALUES"));
			
			foreach($array as $item)
			{

				$tmp = explode(" ", $item[0]);
				$b[$item[0]] = $item;
				$b[$item[0]][0] = $month[$tmp[0]-1]." ".$tmp[1];
				$b[$item[0]][2] = $b[$item[0]][0].$b[$item[0]][2];
				$b[$item[0]]["month"] = ($tmp[0]-1);
			}
			$array = $b;
		}
	}

	//Erstellt den HTML Code für den Marketingcode Filter
	public function getMarketingForm()
	{
		if($this->hasMarkCodes){
			$oLang = oxLang::getInstance();
			$form = $oLang->translateString('HDIREPORT_FMARKETING').': <br><select class="chaval" name="markfilter" width="50"><option value="ohne" selected="selected">'.$oLang->translateString('HDIREPORT_NOTFILTER').'</option>';
			$oDB = oxDb::getDb();
			$rs =  $oDB->execute( "SELECT DISTINCT oxkeycode FROM oxorder");
			if(is_object($rs)){
				$a = $rs->GetArray();
				foreach($a as $code)
				{
					$form .= "<option value=\"".$code[0]."\" ".(($code[0] == $this->markfilter)? "selected":"")." >$code[0]</option>";
				}
			}
			$form .="</select>";
			return $form;
		}
		return "";
	}
}