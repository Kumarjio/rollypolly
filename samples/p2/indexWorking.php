<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Front End</title>
</head>

<body>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

<script type="text/javascript" src="js/jquery.maphilight.min.js"></script>
<script type="text/javascript">
    $(function () {
        //$('.mapHiLight').maphilight({ stroke: false, fillColor: '009DDF', fillOpacity: 1 });
        $('.map').maphilight();//,"alwaysOn":true
    });

var selwidth = 300;
var timeout;
$( document ).ready(function() {
/*
        $( "#contentData" )
          .mouseenter(function() {
            //console.log('mouseenter div');
              clearTimeout(timeout);
          })
          .mouseleave(function() {
            //console.log('mouseleave div');
            timeout = setTimeout(function() {$( "#contentData" ).dialog( "close" )}, 4000);
          });
        
      $( "#contentData" ).dialog({
          autoOpen: false,
          position: { my: "left", at: "right", of: '#mainImage' },
          width: selwidth
      });*/
      /*$( "#position_1" ).mouseover(function() {
         clearTimeout(timeout);
         $('#contentbody').html('new text 1');
         $( "#contentData" ).dialog( "close" );
         $( "#contentData" ).dialog( "open" );
         timeout = setTimeout(function() {$( "#contentData" ).dialog( "close" )}, 4000);
      });*/
});
</script>

<style type="text/css">
.imglist {
  max-width: 100px;
}
.titleText {
  font-size:11px;
}
</style>
<style type="text/css">
body {
 font-family:Verdana;
 font-size: 11px; 
}
</style>

<div id="dialog-hat" title="Hat">
<table border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td valign="top"><img src="../p1/docs/womenshat2.jpg" class="imglist" /></td>
    <td valign="top"><a href="#" class="titleText"><b>Women's Helen Kaminski '9 Kaelo' Raffia Straw Hat</b></a><br /><a href="http://nordstrom.com" target="_blank" class="titleText">nordstrom.com</a></td>
  </tr>
</table>
</div>
<div id="dialog-earing" title="Earrings">
<table border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td valign="top"><img src="../p1/docs/square-earrings-gold2.jpg" class="imglist" /></td>
    <td valign="top"><a href="#" class="titleText"><b>Gold Square Dangle Set</b></a><br /><a href="http://gilt.com" target="_blank" class="titleText">gilt.com</a></td>
  </tr>
</table>
</div>
<div id="dialog-pinktop" title="Pink Tank Top">

<table border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td valign="top"><img src="../p1/docs/pinktop.jpg" class="imglist" /></td>
    <td valign="top"><a href="#" class="titleText"><b>Bella - Ladies' Flowy Racerback Tank -8800</b></a><br /><a href="http://customtshirtprinting.com" target="_blank" class="titleText">customtshirtprinting.com</a></td>
  </tr>
</table>
</div>



<img src="../p1/docs/main.jpg" width="800" height="527" usemap="#Map" id="mapMainImage" />
<map name="Map">
  <area shape="poly" coords="164,224,212,170,206,77,276,28,324,19,372,28,391,54,404,87,415,111,451,143,497,173,475,195,442,212,416,227,414,184,427,159,389,151,316,162,260,172" href="http://google.com" target="_blank" alt="Hat" id="hat">
  <area shape="poly" coords="260,205,258,228,264,254,267,268,274,265,269,258" href="http://yahoo.com" target="_blank" alt="Earings" id="earing">
  <area shape="poly" coords="103,342,153,331,147,375,150,400,165,433,188,461,228,473,272,470,312,460,340,437,355,415,367,391,383,360,398,337,407,316,438,325,454,333,453,360,444,387,434,431,428,475,422,509,423,523,381,523,310,522,230,524,140,522,124,525,123,498,115,461,109,438,103,402,99,376" href="http://msn.com" target="_blank" alt="Pink Top" id="pinktop">
</map>
<script>
   $(function() {
      selwidth = 300;
      $( "#dialog-hat" ).dialog({
          autoOpen: false,
          position: { my: "left", at: "right top", of: '#mapMainImage' },
          width: selwidth
      });
      $( "#hat" ).mouseover(function() {
         $( "#dialog-hat" ).dialog( "open" );
      });
      $( "#dialog-earing" ).dialog({
         autoOpen: false,
          position: { my: "left", at: "right center", of: '#mapMainImage' },
          width: selwidth
      });
      $( "#earing" ).mouseover(function() {
         $( "#dialog-earing" ).dialog( "open" );
      });
      $( "#dialog-pinktop" ).dialog({
         autoOpen: false,
          position: { my: "left", at: "left top", of: '#mapMainImage' },
          width: selwidth
      });
      $( "#pinktop" ).mouseover(function() {
         $( "#dialog-pinktop" ).dialog( "open" );
      });
   });
</script>

</body>
</html>
