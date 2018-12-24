<?php

include "RECLINATHON_CONTEXT.php";

//$currenttime = round(1000 * (microtime(true) + time()));
//$duration = $START + $TIMER - $currenttime;

$rcx = new RECLINATHON_CONTEXT();
$CAPTAIN_SEASON = $rcx->GetCurrentSeason();

if ($_POST["ContextID"] != '')
{
    $ContextID = $_POST["ContextID"];
}
else if ($_GET["ContextID"] != '')
{
    $ContextID = $_GET["ContextID"];
}
else
{
    $query = "SELECT ContextID FROM RECLINATHON_CONTEXT WHERE TimeStamp <= '" . date("U") . "' AND Season = '" . $CAPTAIN_SEASON . "' ORDER BY TimeStamp DESC";
    $result = $rcx->query($query);
    if (!$result)
    {
        echo "ERROR GETTING CONTEXT LIST!";
        exit();
    }
    $row = mysql_fetch_assoc($result);
    $ContextID = $row["ContextID"];
}

if ($ContextID == ''       ||
    !$rcx->Load($ContextID) )
{
    echo "Context Record not found.<BR>";
    exit();
}

$StartTime = round(microtime(true) * 1000);
$TimeRemaining = $rcx->GetTimeRemaining();

?>

<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="rtt.css" />

<script language="JavaScript">
<!-- Begin

var  localstart = 0;
var  refreshtime = 0;
var  duration = 0;
var  timerinterval = 200;
var  refreshinterval = 10000;

function ajaxFunction()
{
var xmlHttp;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    try
      {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    catch (e)
      {
      alert("Your browser does not support AJAX!");
      return false;
      }
    }
  }
  xmlHttp.onreadystatechange=function()
    {
    if(xmlHttp.readyState==4)
      {
      //duration = ;
      var localdate = new Date();
      localstart = localdate.getTime();
      refreshtime = duration - (duration % refreshinterval);
      //refreshstart = localdate.getTime();
      //document.sw.beg2.value = refreshtime;
      }
    }
  xmlHttp.open("GET","ajaxtest.php",true);
  xmlHttp.send(null);
  }

function Days(data) {

	return ( Math.floor(data/(1000*60*60*24)) );
}

function Hours(data) {

	return ( Math.floor(data/(1000*60*60)) % 24 );
}

function Minutes(data) {

	return ( Math.floor(data/(1000*60)) % 60 );
}

function Seconds(data) {

	return ( Math.floor(data/1000) % 60);
}

function Display(days,hours,min,sec) {

	var disp = "";

       if(days>0) disp +=days+" Day";
       if(days>1) disp +="s";
       if(days>0) disp += " ";

       if(hours<=9) disp += "0";

       disp +=hours+":";
 
	if(min<=9) disp += "0";

	disp+=min+":";

	if(sec<=9) disp+="0"+sec;

	else disp+=sec;

	return(disp); 
}

function SetAnswerTimer() {

	var answerinterval=20000/2;
	setTimeout("ShowAnswer()", answerinterval);
}

function ShowAnswer() {

	document.getElementById('DowntimeAnswer').style.display="";

}

function Down() {

	var localdate = new Date();
	localstart = localdate.getTime();
	refreshstart = localdate.getTime();
       
       duration = <?php echo $TimeRemaining; ?>;
       duration *= 1000;

	//ajaxFunction();

	DownRepeat();
}

function DownRepeat() {
	
	var localdate = new Date();
	var localtime = localdate.getTime();

	var timeleft = duration - (localtime - localstart);
 	if(timeleft < 0)
	{
	  timeleft = 0;
	}

	if(timeleft < refreshtime)
	{
	  //ajaxFunction();
     	}

	var cdays = 1*Days(timeleft);
       var chours = 1*Hours(timeleft);
	var cmin = 1*Minutes(timeleft);
	var csec = 0+Seconds(timeleft);

	document.sw.disp2.value=Display(cdays,chours,cmin,csec);

	setTimeout("DownRepeat()", timerinterval); 	
}

function createXMLHttpRequest() 
{
	try { return new XMLHttpRequest(); } catch(e) {}
	try { return new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) {}
	alert("XMLHttpRequest not supported");
	return null;
}

function RemoveClip(clipId)
{
	var xhReq = createXMLHttpRequest();
	xhReq.open("GET", "RemoveVideoClip.php?vcid="+clipId, true);
	xhReq.onreadystatechange = function() {
		if (xhReq.readyState != 4) { return; }
		
		window.location.reload(true);
	};
	xhReq.send(null);
}

function AddClip(contextId)
{
	var clipToAdd = document.getElementById("videoClipToAdd");
	var clipId = clipToAdd.value;
	var xhReq = createXMLHttpRequest();

	xhReq.open("GET", "AddVideoClip.php?vcid="+clipId+"&contextId="+contextId, true);
	xhReq.onreadystatechange = function() {
		if (xhReq.readyState != 4) { return; }
		
		window.location.reload(true);
	};
	xhReq.send(null);
}

// End -->
</script>

</HEAD>
<BODY bgcolor='white' onload='Down();SetAnswerTimer()' CLASS='noborder'>

<?php

//----------------
// NEW TITLE DIV
// ADDED BY CA
//----------------
$rcx->DisplayTitleLogo();


echo "<BR><CENTER><TABLE CLASS='RttFrame'>";

//----------------
// OLD TITLE TR
// REMOVED BY CA
//----------------
//echo "<TR>";
//  echo "<TD COLSPAN='2' CLASS='RttFrame'>";
//    echo "<TABLE CLASS='title'><TR><TD CLASS='title'><H1>LA-Z-DUDE Reclinathon:  Winter 2008</H1></TD></TR></TABLE><BR>";
//  echo "</TD>";
//echo "</TR>";


echo "<TR>";
  echo "<TD CLASS='RttFrame'>";
    $rcx->DisplayCaptainModule();
  echo "</TD>";
  echo "<TD CLASS='RttFrameRight'><br />";
    $rcx->DisplayCaptainDowntimeModule(true);
  echo "</TD>";
echo "</TR>";
echo "<TR>";
  echo "<TD CLASS='RttFrame'>";
    echo "<BR>";
  echo "</TD>";
  echo "<TD CLASS='RttFrameRight'>";
    echo "<BR>";
  echo "</TD>";
echo "</TR>";
echo "<TR>";
  echo "<TD CLASS='RttFrame'>";
    if ($rcx->HasMovie())
    {
        $rcx->GetMovie()->DisplayModule();
    }
  echo "</TD>";
  echo "<TD CLASS='RttFrameRight'>";
	$rcx->DisplayCaptainHistoryModule();
  echo "</TD>";
echo "</TR>";
echo "</TABLE></CENTER>";

?>
