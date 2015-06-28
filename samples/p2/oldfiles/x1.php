<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<img id="mainImage" src="http://mkgxy.consultlawyers.us/samples/p1/docs/main.jpg" width="800" height="527" usemap="#Map" />
<map name="Map">
  <area shape="poly" coords="164,224,212,170,206,77,276,28,324,19,372,28,391,54,404,87,415,111,451,143,497,173,475,195,442,212,416,227,414,184,427,159,389,151,316,162,260,172" href="http://google.com" target="_blank" alt="Hat" id="position_1">
  <area shape="poly" coords="260,205,258,228,264,254,267,268,274,265,269,258" href="http://yahoo.com" target="_blank" alt="Earings" id="position_2">
  <area shape="poly" coords="103,342,153,331,147,375,150,400,165,433,188,461,228,473,272,470,312,460,340,437,355,415,367,391,383,360,398,337,407,316,438,325,454,333,453,360,444,387,434,431,428,475,422,509,423,523,381,523,310,522,230,524,140,522,124,525,123,498,115,461,109,438,103,402,99,376" href="http://msn.com" target="_blank" alt="Pink Top" id="position_3">
</map>
<style type="text/css">
.imglist {
  max-width: 100px;
}
.titleText {
  font-size:11px;
}
</style>
<div id="contentData" title="contentData">
<table border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td valign="top"><img id="contentImage" src="http://mkgxy.consultlawyers.us/samples/p1/docs/womenshat2.jpg" class="imglist" /></td>
    <td valign="top"><span id="contentbody"><b>Women's Helen Kaminski '9 Kaelo' Raffia Straw Hat</b></span><br /><span id="contenturl"><a href="http://nordstrom.com" target="_blank" class="titleText">nordstrom.com</a></span></td>
  </tr>
</table>
</div>

<script>
   $(function() {
      var selwidth = 300;
      var timeout;
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
      });
      $( "#position_1" ).mouseover(function() {
         clearTimeout(timeout);
         $('#contentbody').html('new text 1');
         $( "#contentData" ).dialog( "close" );
         $( "#contentData" ).dialog( "open" );
         timeout = setTimeout(function() {$( "#contentData" ).dialog( "close" )}, 4000);
      });
      $( "#position_2" ).mouseover(function() {
         clearTimeout(timeout);
         $('#contentbody').html('new text 2');
         $( "#contentData" ).dialog( "close" );
         $( "#contentData" ).dialog( "open" );
         timeout = setTimeout(function() {$( "#contentData" ).dialog( "close" )}, 4000);
      });
      $( "#position_3" ).mouseover(function() {
         clearTimeout(timeout);
         $('#contentbody').html('new text 3');
         $( "#contentData" ).dialog( "close" );
         $( "#contentData" ).dialog( "open" );
         timeout = setTimeout(function() {$( "#contentData" ).dialog( "close" )}, 4000);
      });
   });
</script>
</body>
</html>