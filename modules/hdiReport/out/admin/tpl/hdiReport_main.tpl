[{*
*
* Piwik Tracking Block
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
*
*}][{if $isjson =="json"}][{$json}][{else}]<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="[{$oViewConf->getModuleUrl('hdiReport','src/hdiReport.css')}]" type="text/css" rel="Stylesheet">
<script src="[{$oViewConf->getModuleUrl('hdiReport','src/amcharts/javascript/amcharts.js')}]" type="text/javascript"></script>
<script src="[{$oViewConf->getModuleUrl('hdiReport','src/amcharts/javascript/raphael.js')}]" type="text/javascript"></script>
<script type="text/javascript">
var selflink = "[{$oViewConf->getSelfLink()}]";
var resourceurl = "[{$oViewConf->getModuleUrl('hdiReport','src/')}]";
var currency = "[{ $oActCur->name }]";
var favView = [{$oView->load_conf()}];
var aLang = new Array();
aLang["date"] = "[{ oxmultilang ident="HDIREPORT_DATE"}]";
aLang["month"] = "[{ oxmultilang ident="HDIREPORT_MONTH"}]";
aLang["value"] = "[{ oxmultilang ident="HDIREPORT_VALUE"}]";
aLang["description"] = "[{ oxmultilang ident="HDIREPORT_DESCRIPTION"}]";
aLang["orders"] = "[{ oxmultilang ident="HDIREPORT_ORDERS"}]";
aLang["soldunits"] = "[{ oxmultilang ident="HDIREPORT_SOLDUNITS"}]";
aLang["product"] = "[{ oxmultilang ident="HDIREPORT_PRODUCT"}]";
aLang["marketing"] = "[{ oxmultilang ident="HDIREPORT_MARKETING"}]";
aLang["shownrevenue"] = "[{ oxmultilang ident="HDIREPORT_SHOWNREVENUE"}]";
aLang["nodata"] = "[{ oxmultilang ident="HDIREPORT_NODATA"}]";
aLang["saved"] = "[{ oxmultilang ident="HDIREPORT_SAVED"}]";
aLang["savederr"] = "[{ oxmultilang ident="HDIREPORT_SAVEDERR"}]";
mv = [{ oxmultilang ident="HDIREPORT_MONTHVALUES"}];
mvs = [{ oxmultilang ident="HDIREPORT_MONTHVALUESSHORT"}];
wv = [{ oxmultilang ident="HDIREPORT_WEEKVALUES"}];
wvs = [{ oxmultilang ident="HDIREPORT_WEEKVALUESSHORT"}];


</script>
<script src="[{$oViewConf->getModuleUrl('hdiReport','src/hdiReport.js')}]" type="text/javascript"></script>
</head>
<body>
<div id="hdicontrol">
<h1>HDI Reports<span id="wait"></span></h1>
<div class="controll">
<form id="diaform" method="post" action="[{$oViewConf->getSelfLink()}]">[{ $oViewConf->getHiddenSid() }] <input type="hidden" name="tpl" value="json" /> <input type="hidden" name="cl"
  value="hdi_report" />
<table>
  <tr valign="top">
    <td valign="top"><b>[{ oxmultilang ident="HDIREPORT_STATISTIC"}]</b><br>
    <input id="radioart1" type="radio" name="art" value="UmsatzDatum" checked><label for="radioart1">[{ oxmultilang ident="HDIREPORT_RBYDATE"}]</label><br>
    <input id="radioart2" type="radio" name="art" value="UmsatzMonat"><label for="radioart2">[{ oxmultilang ident="HDIREPORT_RBYMONTH"}]<br></label>
    <input id="radioart3" type="radio" name="art" value="UmsatzProdukt"><label for="radioart3">[{ oxmultilang ident="HDIREPORT_RBYPRODUCT"}]<br></label>
    <input id="selvars" class="selvars chaval readonly" type="checkbox" name="groupvars" value="checked"><label class="selvars" for="selvars">[{ oxmultilang ident="HDIREPORT_GROUPVARIANTS"}]<br>
    </label> [{if $hasMarketing}]<input id="radioart4" type="radio" name="art" value="UmsatzMarketingCode"><label for="radioart4">[{ oxmultilang ident="HDIREPORT_RBYMARKETING"}]<br></label>
    [{/if}]
    <hr>
    <b>[{ oxmultilang ident="HDIREPORT_DIAGRAM"}]</b><br>
    <input id="radiochart1" type="radio" name="chart" value="column" checked><label for="radiochart1">[{ oxmultilang ident="HDIREPORT_BARCHART"}]</label> <br>
    <input id="radiochart2" type="radio" name="chart" value="line"><label for="radiochart2">[{ oxmultilang ident="HDIREPORT_LINECHART"}]</label><br>
     <input id="radiochart3" type="radio" name="chart" value="table"><label for="radiochart3">[{ oxmultilang ident="HDIREPORT_TABLEONLY"}]</label>
   <hr>
	<b>[{ oxmultilang ident="HDIREPORT_DISPLAYVALUES"}]</b><br>
    <input id="selnetto" class="chaval selnetto readonly" type="checkbox" name="netto" value="checked" checked><label for="selnetto" class="selnetto readonly">[{ oxmultilang ident="HDIREPORT_BRUTTO"}]</label><br>
    <input id="selvers" type="checkbox" name="versand" value="checked" checked class="readonly"><label for="selvers" class="readonly">[{ oxmultilang ident="HDIREPORT_ADDCOSTS"}]</label></td>

   </td>
   	<td>
	<b>[{ oxmultilang ident="HDIREPORT_SORT"}] </b><input name="aufsteigend" class="chaval" type="checkbox">[{oxmultilang ident="HDIREPORT_ASC"}]<br>
	<input id="selsort0" type="radio" name="sort" class="chaval" value="ohne" checked><label for="selsort0">[{ oxmultilang ident="HDIREPORT_SDEFAULT"}]</label><br>
    <input id="selsort1" type="radio" name="sort" class="chaval" value="Umsatz"><label for="selsort1">[{ oxmultilang ident="HDIREPORT_SBYREVENUE"}]</label><br>
	<input id="selsort2" type="radio" name="sort" class="chaval" value="Bestellungen"><label for="selsort2">[{ oxmultilang ident="HDIREPORT_SBYORDERS"}]</label><br>
	<input id="selsort3" type="radio" name="sort" class="chaval" value="Produkten"><label for="selsort3">[{ oxmultilang ident="HDIREPORT_SBYSOLDUNITS"}]</label><br>
    <hr><b>[{ oxmultilang ident="HDIREPORT_OPTIONS"}]<br></b>
	<input id="selbest" type="checkbox" name="bestell" value="checked"><label for="selbest">[{ oxmultilang ident="HDIREPORT_OSHOWORDERS"}] <span style="color:#aaa;">---</span></label><br>
	<input id="seleinheit" type="checkbox" name="einheiten" value="checked"><label for="seleinheit">[{ oxmultilang ident="HDIREPORT_OSHOWUNIT"}] <span style="color:#2b92aa;">---</span></label><br>
	<input id="seltabl" type="checkbox" name="table" value="checked"><label for="seltabl">[{ oxmultilang ident="HDIREPORT_OSHOWTABLE"}]</label><br>
    <td valign="top"><b>[{ oxmultilang ident="HDIREPORT_FILTER"}]</b> <br>
    [{ oxmultilang ident="HDIREPORT_FSTARTDATE"}]<br>
    <input type="text" class="date" id="start" name="startdate" value="[{$startdate}]" /><br>
    [{ oxmultilang ident="HDIREPORT_FENDDATE"}]<br>
    <input type="text" class="date" id="end" name="enddate" value="[{$enddate}]" /><br>
    <a class="afake" onClick="setDate(1)">&raquo;[{ oxmultilang ident="HDIREPORT_FTHISMONTH"}]</a><br>
    <a class="afake" onClick="setDate(2)">&raquo;[{ oxmultilang ident="HDIREPORT_FLASTMONTH"}]</a><br>
    <a class="afake" onClick="setDate(3)">&raquo;[{ oxmultilang ident="HDIREPORT_FTHISYEAR"}]</a><br>
    <a class="afake" onClick="setDate(4)">&raquo;[{ oxmultilang ident="HDIREPORT_FLASTYEAR"}]</a><br>
    <a class="afake" onClick="setDate(5)">&raquo;[{ oxmultilang ident="HDIREPORT_FALL"}]</a></td>
    <td valign="top"><br>[{$oView->getMarketingForm()}]

    [{ oxmultilang ident="HDIREPORT_FBYPRODUCT"}]<br>
    <input type="text" name="prodfilter" class="chaval" value="">
    <br>
	[{ oxmultilang ident="HDIREPORT_FMINREVENUE"}]<br>
<input type="text" class="chaval" name="minumsatz"><br>
	[{ oxmultilang ident="HDIREPORT_FMAXREVENUE"}]<br>
<input type="text" class="chaval" name="maxumsatz"><br>
	[{ oxmultilang ident="HDIREPORT_FLIMIT"}]<br>
	<input type="text" name="limit" class="chaval" >
	</td>
  </tr>
</table>
<a id="saveFav" class="afake">[{ oxmultilang ident="HDIREPORT_SAVE"}]</a>
</form>
</div>
</div>
<div id="objects">
<div id="chart" style="min-height: 500px;"></div>
<div id="table" ></div>
</div>
</body>
</html>
[{/if}]
