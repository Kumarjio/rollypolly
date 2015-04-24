<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/Donations_theme1.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Base page</title>
<!-- InstanceEndEditable -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>


<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/theme1.css" rel="stylesheet">
<link href="assets/css/site.css" rel="stylesheet">


<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">

<!--[if lt IE 7]>
<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome-ie7.min.css" rel="stylesheet">
<![endif]-->

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
<![endif]-->

<link rel="shortcut icon" href="assets/ico/favicon.ico" type="image/x-icon">
<link rel="icon" href="assets/ico/favicon.ico" type="image/x-icon">

<?php 
require_once('inc_category.php'); 

?>

<!-- InstanceBeginEditable name="head" -->
<meta charset="UTF-8">

<!-- InstanceEndEditable -->
</head>
<body>
<div class="wrap">
<section>
<nav class="navbar-default navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
       <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="/">godonateme.com</a>
    </div>
    
    <?php include('inc_menu.php'); ?>
    
  </div>
</nav>
</section>
<section class="top-section">
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <?php include('inc_googleadsense.php'); ?>
    </div>
  </div>
</div>
</section>

<section>
<div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
        <?php echo $leftSideCategoryLink; ?>
      </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<!-- InstanceBeginEditable name="EditRegion5" -->
<style type="text/css">
/* Navy Theme */

body{
    background: url(navy/body_bkgrd.jpg) repeat;
}
.hd{
    border-top:7px solid #053C79;
}
/* Big Donate Button */
.btn1{
    background: url(navy/btn.jpg) repeat-x 0 0;
    text-shadow: 1px 1px #0C2342;
}
/* Smaller button (used in boxes) */
.btn1.small{
    background: url(navy/small_btn.jpg) repeat-x 0 0;
}
/* Footer border top color */
.footbase{
    border-top:7px solid #053C79;
}
/* Theme text color */
.txt1 {
    color: #053C79 !important;
}
/* small donate button */
.smdtn{width:107px; background: url(navy/sm_dnt.png) no-repeat 0 0;}
.smdtn:hover{background: url(navy/sm_dnt.png) no-repeat 0 -29px;}
.smdtn:active{background: url(navy/sm_dnt.png) no-repeat 0 -58px;}

/* small pledge button */
.pld{width:107px; background: url(navy/pledge.jpg) no-repeat 0 0;}
.pld:hover{background: url(navy/pledge.jpg) no-repeat 0 -29px;}
.pld:active{background: url(navy/pledge.jpg) no-repeat 0 -58px;}



/* disclosure triangle for toggle on recent donors */
.section_head .toggle{
    background: white url(navy/dwnarw.png) no-repeat 69px 14px;
}
.section_head .toggleoff {
    background:  url(navy/dwnarw.png) no-repeat 70px 15px;
}

/* FB and Twitter Icon for reward badges */
.rew_icon.rew_fb{ background: url(navy/rew.png) no-repeat 0 0}
.rew_icon.rew_tw{ background: url(navy/rew.png) no-repeat 0 -30px;}

/* Solid top bars */
.bar {background:#5F7E19;}

/* Text color */
.main-text {color:#5F7E19;}

/* Lighter background color */
.light-bkgd {background:#E7EDF2;}

/* Progress bar */
.details .bar .fill{
    background: url(navy/fill.png) no-repeat;
}

.topbar {
    background: #053C79;
}

.gfm_ad{
    background-color: #EFF6FF;
}
.donationamount,
.zeros,
.dollarsign,
.donationcommentbox .photo,
.headbar,
.gfm_lb_main,
.section_head,
.doner .rewbox,
.light-bkgd,
.reward .title,
.wishlist .title,
.rewardna:hover .title,
.donationform .autocomplete a:hover,
.selected,
.gfm_dropdown_option:hover,
.gfm_dropdown_option.odd:hover,
.greencontainer,
.donationform .dropdown .odd.selected
{
    background: #E7EDF2;
}

.fbcomgood{background-color: #E7EDF2;}


.xsmallbtn {
    background: url(navy/xsmall_btn.jpg) 0 0;
}
.fbs {
    background:url(navy/fb_bkgd.jpg) 0 0;
    text-shadow: 1px 1px #091B37;
}

.a{
    border-left: 2px solid #BBBBBB;
    border-right: 2px solid #BBBBBB;
    border-bottom: 2px solid #BBBBBB;
}

.pg_msg a {
    color: #053C79 !important;
}
.sub{
    background: url("navy/submit.jpg") no-repeat scroll 0 0
}
.sub:hover{background: url(navy/submit.jpg) no-repeat 0 -29px;}
.sub:active{background: url(navy/submit.jpg) no-repeat 0 -58px;}


/* Main style sheet for Donate 2014  */

/* Index

    • Page Boxes
    • Top Nav Head Bar

*/
* {
    margin: 0;
    -webkit-font-smoothing:antialiased;
}

.fr{
    float: right;
}
.fl {
    float:left;
}
.round5{
    border-radius: 5px;
}
.center {text-align:center;}
.ml20{margin-left: 20px;}
.ml60{margin-left: 60px;}
.ml10{margin-left: 10px;}
.ml13{margin-left: 13px;}
.ml30{margin-left: 30px;}
.ml40{margin-left: 40px;}
.ml50{margin-left: 50px;}
.ml70 {margin-left: 70px;}
.mt0{margin-top: 0 !important;}
.mt10{margin-top: 10px;}
.mt20{margin-top: 20px; !important;}
.mt30{margin-top: 30px !important;}
.mt40{margin-top: 40px !important;}
.mt200{margin-top: 200px !important;}
.mr5{margin-right: 5px !important;}
.mr10{margin-right: 10px !important;}
.mr20{margin-right: 20px !important;}
.mb10{margin-bottom: 10px;}
.mb20{margin-bottom: 20px;}
.mb30{margin-bottom: 30px !important;}
.mb40{margin-bottom: 40px !important;}
.mb50{margin-bottom: 50px !important;}
.mb60{margin-bottom: 60px !important;}

.p20 { padding: 20px !important;}
.p30 { padding: 30px !important;}

.clear { clear: both; }

/* Page Boxes*/
.a{
    display: block;
    width: 932px;
    margin: 0 auto;
    font-family: lato, sans-serif;
    padding: 0 20px;
    border-bottom-right-radius:8px;
    border-bottom-left-radius: 8px;
    background: #ffffff;
    border-left: 2px solid #DDDDDD;
    border-right: 2px solid #DDDDDD;
    border-bottom: 2px solid #DDDDDD;
}


.a a{
    color: #5F7E19;
    text-decoration: none;
}
.a a:hover{
    text-decoration: underline;
}

.afooter{
    display: block;
    width: 100%;
    height: 65px;
    background: url(images/bigdash.png) repeat-x 0 0;

}
.afooter .aftxt{
    float: left;
    font-family: lato, sans-serif;
    font-weight: 300;
    color: #666;
}
.afooter .aftxt a{
    color: #666;
}

.afooter .ogo{
    float: right;
    margin-top: 13px;
    width: 128px;
    height: 41px;
    background: url(images/detailslogo.jpg);
}



/* FB & Twitter Share Btns*/
.fbshare,.twshare,.smdtn,.fblike,.pld{
    float: left;
    width: 95px;
    height: 29px;
}
.fbshare{background: url(images/donate_head_btns.png) no-repeat 0 -174px;}
.fbshare:hover{background: url(images/donate_head_btns.png) no-repeat 0 -203px;}
.fbshare:active{background: url(images/donate_head_btns.png) no-repeat 0 -232px;}

.twshare{background: url(images/donate_head_btns.png) no-repeat 0 -87px;}
.twshare:hover{background: url(images/donate_head_btns.png) no-repeat 0 -116px;}
.twshare:active{background: url(images/donate_head_btns.png) no-repeat 0 -145px;}

.fblike{background: url(images/fblike.png) no-repeat 0 0px;}
.fblike:hover{background: url(images/fblike.png) no-repeat 0 -29px;}
.fblike:active{background: url(images/fblike.png) no-repeat 0 -58px;}

.sub{ width:107px; height:29px; float:right; margin-right: 1px; background: url(images/submit.jpg) no-repeat 0 0px;}
.sub:hover{background: url(images/submit.jpg) no-repeat 0 -29px;}
.sub:active{background: url(images/submit.jpg) no-repeat 0 -58px;}



    /*large facebook share*/
.fbs{
    display: block;
    width: 264px;
    height: 39px;
    line-height:39px;
    background-position:0 0;
    text-align:center;
    color:#ffffff;
    border-radius:5px;
    -webkit-font-smoothing: antialiased;
    font-family:Montserrat, sans-serif;
    text-transform:uppercase;
    font-size:14px;
    font-weight:700;
    text-shadow:none;
    overflow:hidden;
}
a.fbs {color:#ffffff;
    text-decoration:none;}
a.fbs:hover {text-decoration:none;}
.fbs:hover{ background-position: 0 -39px;
    text-decoration:none;}
.fbs:active{ background-position: 0 -78px;
text-decoration:none;
line-height:41px;}


/*ORIGINAL FB blue background class*/
.fbblue {
    background:url(images/fb_background.jpg) !important;
}

    /*logo button in details block*/
.delogo{
    display: block;
    margin: 14px auto 0;
    width: 128px;
    height: 41px;
    /*background: url(images/detailslogo.jpg) no-repeat 0 4px; Was cutting off bottom of logo*/
    background: url(images/detailslogo.jpg) no-repeat 0 0px;
}


.topbar {
    width:974px;
    height:7px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    margin: 100px auto 0;
}

/* Donate Page */

.pagetitle{
    font-family: 'Montserrat', sans-serif;
    display: block;
    font-size: 46px;
    /*Changed line height because descenders were being cut off*/
    line-height: 53px;
    color:#5F7E19;
    text-align: center;
    overflow: hidden;
    white-space: nowrap;
    padding-top: 30px;
    margin: 0 auto 30px 0;
    text-overflow: ellipsis;
    width: 932px;
    letter-spacing: -1px;
}
.pagetitle.long{
    letter-spacing: -2px;
}
.fundphoto{
    float: left;
    width: 594px;
    height: 395px;
    overflow: hidden;

}
.fundphoto.noimg{
    background: url(images/nophoto.jpg) no-repeat 0 0;
}
.fundphoto img{
    width: 594px;
}
.updatephoto{
    float: left;
    width: 554px;
    height: 355px;
    overflow: hidden;
    margin: 20px 0;
}
.update_content img,.pg_msg img{
    width: 100%;
    border-radius: 5px;
    margin: 20px 0;

}
.pg_msg iframe {
    margin-top: 20px;
}
.update_content .caption{

    font-weight: 400;
    color:#666;
    font-size: 13px;
    line-height: 15px;
}
a.askforphoto{
    float: left;
    margin-left:200px;
    margin-top: 320px;
    font-size: 14px;
    line-height: 16px;
    color: #999;
    text-shadow: 1px 1px white;
}
.details{
    float: right;
    width: 304px;
    height: 393px;
    border: 1px solid #dddddd;
    -webkit-box-shadow: 1px 2px 2px 0px rgba(0, 0, 0, 0.1);
    -moz-box-shadow:    1px 2px 2px 0px rgba(0, 0, 0, 0.1);
    box-shadow:         1px 2px 2px 0px rgba(0, 0, 0, 0.1);
}
.details a.loc,.details a.cat{
    float: left;
    padding-left: 20px;
    font-size: 12px;
    color: #666;
}
.details .loc{
    background: url(images/loc_cat.png) no-repeat 0 10px;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 117px;
    overflow: hidden;
}
.details a.cat { float: right; }
.details .cat{
    background: url(images/loc_cat.png) no-repeat 0 -18px;
}
.details .raised {
    font-size: 46px;
    line-height: 48px;
    letter-spacing: -1px;
    color: #333;
    font-family: Montserrat, sans-serif;
    padding-top:20px;
}
.details .raised.smlr {
    font-size: 38px;
}

.details .raised .of {
    font-family: 'lato', sans-serif;
    font-size: 16px;
    line-height: 48px;
    letter-spacing: 0;
    color: #999;
}

.details .raised .goal {
    font-family: 'lato', sans-serif;
    font-size: 16px;
    line-height: 48px;
    letter-spacing: 0;
    color: #666;
}
.details .raised .cur {
    font-family: 'Montserrat', sans-serif;
    font-size: 30px;
    line-height: 32px;
    vertical-align: 10px;
    color: #333;
}
.details .raised .cur.smlr {
    font-size: 25px;

}
.details .bar{
    display: block;
    width: 264px;
    height: 34px;
    background: url(images/bar.png) no-repeat;
}
.details .bar .fill{
    float: left;
    width: 253px;
    height: 26px;
    margin: 4px 0 0 5px;
}
.details .time{
    margin-top: 10px;
    font-size: 15px;
    line-height: 17px;
    font-family: lato, sans-serif;
}
.details .time span{
    font-family: lato, sans-serif;
    color: #333;
    font-weight: 400;
}
.details .time.aon{
    padding-left: 27px;
    background: url(images/aonclock.png) no-repeat 0 0;
    line-height: 20px;
}
.details .charity{
    width: 100%;
    margin-top: 18px;
    display: block;
    font-size: 13px;
    line-height: 19px;
    color:#333333;
    text-shadow: none;
    font-family: lato, sans-serif;

}
.details .txid{
    display: block;
    color: #666;

}
.details .charity .chartitle{
    float: left;
    padding-left: 27px;
    background: url(images/clk2.png) no-repeat 0 0;
    line-height: 20px;
    font-size: 15px;
}
.details .charity .lmore{
    float: right;
    line-height: 20px;
    font-size: 14px;
}
.details .charity .charityline{
    display: block;
    width: 100%;
    height: 1px;
    margin-top: 5px;
    margin-bottom: 5px;
    background: url(images/dash.png) repeat-x;
}
.details .charity .charlink{
    color: #666666 ;
    text-decoration: none;
}


.donatebtn{
    text-align: center;
    margin-top: 30px;
    display: block;
    width: 264px;
    height: 47px;
    font-size: 28px;
    line-height: 30px;
    font-family: montserrat, sans-serif;
    font-weight: 600;
    padding-top: 17px;
    border-radius:5px;
}
a.donatebtn{
    color: white;
    text-decoration: none;
}
a.donatebtn:hover{
    color: white;
    text-decoration: none;
}
.btn1:hover{background-position:  0 -64px;}
.btn1:active{background-position:  0 -128px; padding-top: 18px; height: 46px;}

.btn1.small:hover{background-position:  0 -54px;}
.btn1.small:active{background-position:  0 -108px; padding-top: 18px; height: 36px;}




.sharebar{
    float: left;
    width: 573px;
    padding: 8px 0 6px 20px;
    border: 1px solid #e8e8e8;
    border-bottom: 3px solid #E3E3E3;
    font-family: 'Montserrat', sans-serif;
}
.sharebar .big{
    float: left;
    font-size: 40px;
    line-height: 49px;
    color: #666666;
}
.sharebar .biglbl{
    float: left;
    font-size: 14px;
    line-height: 16px;
    margin-left: 10px;
    margin-top: 8px;
    letter-spacing: 0;
    color: #999999;
}
.sharebar .count{
    float: left;
    font-size: 24px;
    line-height: 26px;
    color: #999999;
    margin-top: 11px;
}
.sharebar .div{
    float: left;
    height: 30px;
    width: 1px;
    margin-left:20px;
    margin-right: 20px;
    background: #dddddd;
    margin-top: 10px;
}
.sharebar .div2{
    float: left;
    height: 30px;
    width: 1px;
    margin-left:10px;
    margin-right: 10px;
    background: #dddddd;
    margin-top: 8px;
}

.sharebar .dash{
    float: left;
    height: 1px;
    width: 553px;
    background: url(images/dash.png) repeat-x 0 0;
}
.sharebar .pglink{
    float: left;
    font-size: 14px;
    line-height: 16px;
    color: #999;
}


.createdby{
    float: right;
    width: 284px;
    height: 41px;
    padding: 11px 10px;
    border: 1px solid #e8e8e8;
    border-bottom: 3px solid #E3E3E3;
    overflow: hidden;
}

.createdby .cbimg{
    float: left;
    width: 40px;
    height: 40px;
    border-radius: 4px;
    margin-right: 15px;
    overflow: hidden;


}
.createdby .cbimg img{
    float: left;
    width: 40px;
}
.createdby .cbdate{
    font-size: 13px;
    line-height: 15px;
    color: #666666;
}

.createdby .cbname{
    font-size: 16px;
    line-height: 30px;
    color: #333333;
    margin-top: 0;
    word-break: break-all;
    overflow: hidden;
}
.createdby .cbname.sm{
    font-size: 13px;
}
.createdby .cbmail{
    float: left;
    width: 16px;
    height: 17px;
    margin-right: 6px;
    margin-top: 6px;
    background: url(images/small_icons.png) no-repeat 0 4px;
}




.donate_left{
    float: left;
    width: 595px;
}
.donate_right{
    float: right;
    width: 306px;
    margin-bottom: 20px;
}
.left_bx{
    float: left;
    width: 593px;
    border: 1px solid #e8e8e8;
    border-bottom: 3px solid #E3E3E3;
    margin-bottom: 30px;
}
.left_bx.rw{
    margin-bottom: 0;
}
.right_bx{
    float: left;
    width: 304px;
    border: 1px solid #e8e8e8;
    border-bottom: 3px solid #E3E3E3;
}
.gfm_ad{
    height: 125px;
    background: url(images/logo.png) no-repeat 18px 20px;
}



.update_content{
    padding: 20px;
    font-size: 18px;
    font-weight:300;
    line-height: 25px;
    color: #000;
    font-family: lato, sans-serif;
    word-wrap: break-word;
}
.shareopts a {
    font-size: 14px;
    line-height: 29px;
    font-weight: 300;
}



.pg_msg{
    display:block;
    font-size: 18px;
    font-weight:300;
    line-height: 25px;
    min-height: 208px;
    color: #000;
    font-family: lato, sans-serif;
    word-wrap: break-word;
}

.camptitle {
    width: 265px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}




.headbar{
    display: block;
    height: 35px;
    line-height: 35px;
    padding: 0 20px;
    font-size: 14px;
    font-family: 'montserrat', sans-serif;
    text-shadow: 1px 1px white;
    color: #666666;
    border-top-right-radius:5px;
    border-top-left-radius:5px;
    border-bottom: 1px solid #e3e3e3;
}

.noinfo{
    display: block;
    height: 35px;
    width: 200px;
    line-height: 35px;
    padding: 0 20px;
    font-size: 14px;
    font-family: 'montserrat', sans-serif;
    text-shadow: 1px 1px white;
    color: #666666;
    border-top-right-radius:5px;
    border-top-left-radius:5px;
    border-bottom: 1px solid #e3e3e3;
}



.section_head{
    position: relative;
    display: block;
    height: 27px;
    background: #F3F9E5;
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;
    border-bottom: 1px solid #DDDDDD;
    color: #666666;
    text-shadow: 1px 1px white;
    padding: 10px 20px 0;
    font-size: 14px;
    line-height: 19px;
    font-family: montserrat, sans-serif;
}



.toggleoff{
    position: absolute;
    width: 70px;
    top: 0;
    right: 10px;
    padding: 9px 11px 11px 11px;
    font-size: 12px;
}
.toggle{
    position: absolute;
    width: 70px;
    top: 0;
    right: 10px;
    padding: 0 10px;
    font-size: 12px;
    border: 1px solid #ddd;
    box-shadow: 1px 2px 2px #ddd;
}
.toggle a{
    line-height: 16px;
    padding: 10px 0 10px 10px;
    width: 80px;
    height: 16px;
    display: block;
    margin-left: -10px;
}
.toggle .togbk{
    width: 100%;
    height: 1px;
    background: #ddd ;
}

.doner{
    padding: 20px;
    border-bottom: 1px solid #ddd;

}
.doner .damt{
    font-size: 30px;
    line-height: 30px;
    margin-top: -7px;
    margin-bottom: 2px;
    color: #5E7F07;
    font-family: montserrat, sans-serif;
    font-weight: 300;
    vertical-align: text-top;
}
.doner .damt span{
    font-size: 18px;
    vertical-align: text-top;

}
.doner .dpic{
    float: left;
    width: 65px;
    height: 65px;
    border-radius: 4px;
    margin-right: 15px;
    overflow: hidden;
}
.doner .dpic img{
    float: left;
    width: 65px;
}


.doner .dname{
    font-size: 16px;
    line-height: 18px;
    color: #333;
    margin-bottom: 5px;
}
.doner .dtime{
    color: #666;
    font-size: 12px;
    line-height: 14px;
}
.doner .dcom{
    color: #666;
    font-size: 16px;
    line-height: 18px;
    margin-top: 15px;
    font-weight: 300;
    word-wrap: break-word;
}

/* gives margin to long names with a picture - christy 3/9/2015 */
.dpic + .ddeat .dname,
.dpic + .ddeat .dtime {
    margin-left: 80px;
}

.donerscroll{
    text-align: center;
    height: 37px;
    font-size: 14px;
    line-height: 37px;
    color:#666;
    background-color: #F8F8F8;
}
.donerscroll span{
    color: #333;
}

.donerscroll .lr{
    width: 40px;
    height: 37px;
}
.donerscroll .lr:hover{
    background-color: #F4F4F4;
}
.donerscroll .pleft{
    float: left;
    border-right: 1px solid #DDDDDD;
    background: url(images/donerscrollarw.png) no-repeat 14px 0;
}

.donerscroll .first {
    opacity:0;
}

.donerscroll .pleft:active{  background: #F2F2F2  url(images/donerscrollarw.png) no-repeat 14px -37px; }

.donerscroll .pright{
    float: right;
    border-left: 1px solid #DDDDDD;
    background: url(images/donerscrollarw.png) no-repeat 16px -74px;
}
.donerscroll .pright:active{ background: #f2f2f2 url(images/donerscrollarw.png) no-repeat 16px -111px; }

.rewbox{
    width: 100%;
    height: 32px;
    border: 1px solid #dddddd;
    text-align: center;
    font-size: 12px;
    line-height: 32px;
    color: #666;
    text-shadow: 1px 1px white;
    clear: both;
}
.lbl1 {
    float: left;
    position: relative;
    Left: 50%;
}
.lbl2 {
    float: left;
    position: relative;
    Left: -50%;
}
.rew_icon{
    display: inline-block;
    width: 30px;
    height: 32px;

}


/* Top Nav Head Bar */
.hd{
    width: 100%;
    height: 51px;
    background: #000 url(images/newBg.png) repeat-x;
    position:fixed;
    top:0;
    left:0;
    z-index:99;
    box-shadow: 1px 1px 5px #999;
    top:0;
    left:0;
}
.hd_main{
    width: 972px;
    height: 51px;
    margin: 0 auto;
}
.hd .hd_main a.logo {
    float: left;
    margin: 6px 0 0 1px;
    width:128px;
    height: 41px;
    background: url(images/logo.gif) no-repeat;
}
.hd a:hover {cursor: pointer;}

.hd .menu{
    height:33px;
    float:left ;
    color: #666;
    padding:18px 10px 0 10px;
    font-size: 14px;
    line-height:16px;
    font-family: 'Lato', sans-serif;
    font-weight: 300;
    letter-spacing: 0;
    text-decoration: none;
    -webkit-font-smoothing: subpixel-antialiased;
}

.hd .menu:hover{background-color: white;}

.hd .menu.si{color:#5F7E19;}

.menusearch{
    width: 32px;
    background: url(images/search.png) no-repeat 14px 17px;
}
.hd_s {
    width: 162px;
    height: 23px;
    margin-top: 11px;
    border-radius: 20px;
    padding: 3px 5px 3px 33px;
    font-family: 'Lato', sans-serif;
    color: #999999;
    font-size:13px;
    font-style:italic;
    border: 1px solid #BFBFBF;
    float:left;
    background: #fff url(images/s.png) no-repeat 12px 7px;
    box-shadow: inset 1px 1px 3px  #D9D9D9;
    outline: none; 
}
.hd_s.text{
    color: #333;
    font-style:normal;
}

.hd_s.active {
    font-size:13px;
    font-style:normal;
    color:#333333;

}


/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* SET TO REMOVE

.hd .newsu{
    background: url(images/signupnav.png);
    width: 79px;
    height:29px;
    float:right;
    margin-top: 12px;
    margin-left:6px;
    margin-right:-2px;
    border-radius: 5px;
    font-family: 'Lato', sans-serif;
    font-size: 14px;
    line-height: 18px;
    text-decoration: none;
    color:white;
    text-align: center;
    text-shadow: 1px 1px 2px #996600;
}
.hd .newsu:hover{background: url(images/signupnav.png) 0 -29px repeat-x;}

.hd .newsu:active{background: url(images/signupnav.png) 0 -58px repeat-x;}

.hd .srm{
    background: url(images/srm.png);
    width: 155px;
    height:29px;
    float:right;
    margin-top: 12px;
    margin-left:6px;
    margin-right:-2px;
}
.hd .srm:hover{background: url(images/srm.png) 0 -29px repeat-x;}

.hd .srm:active{background: url(images/srm.png) 0 -58px repeat-x;}

.hd .d{
    background: url(images/d.png);
    width: 98px;
    height:29px;
    float:right;
    margin-top: 12px;
    margin-left:6px;
    margin-right:-2px;
}
.hd .d:hover{background: url(images/d.png) 0 -29px repeat-x;}

.hd .d:active{background: url(images/d.png) 0 -58px repeat-x;}

 */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */

.hd .br {
    width: 2px;
    height: 42px;
    background: url(images/hd_br.png) no-repeat;
    float: right;
    margin:4px 0 0 0;
}
.hd .btn{
    padding: 4px 12px 1px 12px;
    height:22px;
    float:right;
    border-radius:5px;
    margin:12px 3px 0 0;
    font-family: 'Lato', sans-serif;
    font-size: 13px;
    line-height: 150%;
    text-decoration: none;
}
.hd .gry{
    background: url(images/hd_btn.png)repeat-x;
    border:1px solid #BABABA;
    color:#666;
}
.gry:hover{background: url(images/hd_btn.png)repeat-x 0 -29px ;}
.gry:active{
    background: url(images/hd_btn.png)repeat-x 0 -58px;
    padding-top:5px;
    height:21px;
}


/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* SET TO REMOVE

.video{
    width: 108px;
    height:33px;
    background: url(images/videoarrow.png) no-repeat 15px 19px;
    float:left ;
    color: #5F7E19;
    margin-left: -1px;
    padding: 18px 15px 0 37px;
    font-size: 14px;
    line-height:18px;
    font-weight: 300;
    font-family: 'Lato', sans-serif;
    cursor: pointer;
    text-decoration: none;
    z-index: 10000;
}
.video:hover{background:white url(images/videoarrow.png) no-repeat 15px -19px;}


*/
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */
/* ///////////// */

.grntop{
    height:33px;
    float:left ;
    color: #5F7E19;
    margin-left: -1px;
    padding: 18px 15px 0 10px;
    font-size: 14px;
    line-height:18px;
    font-weight: 300;
    font-family: 'Lato', sans-serif;
}


.util{
    width: 174px;
    padding-left: 120px;
    padding-top: 15px;
    height: 50px;
    background: url(images/grey_logo.png) no-repeat 10px 0;
    font-size: 14px;
    line-height: 16px;
}
.util:hover{background: url(images/grey_logo.png) no-repeat 10px -65px;}
.util:active{background: #f9f9f9 url(images/grey_logo.png) no-repeat 10px -65px;}


a.util{
    color: #666666;
}
a.util .whatis{
    font-size: 16px;
    line-height: 18px;
    display: block;
    color:#333333;
    margin-bottom: 3px;
}
.util_btn{
    float: right;
    width: 65px;
    height: 20px;
    font-size: 11px;
    text-align: center;
    margin-left: 7px;
    padding-top: 45px;

}
.report{background: url(images/grey_btns.png) no-repeat 10px 0;}
.report:hover{background: url(images/grey_btns.png) no-repeat 10px -65px;}
.report:active{background: #f9f9f9 url(images/grey_btns.png) no-repeat 10px -65px;}


.poster{background: url(images/grey_btns.png) no-repeat 10px -130px;}
.poster:hover{background: url(images/grey_btns.png) no-repeat 10px -195px;}
.poster:active{background: #f9f9f9 url(images/grey_btns.png) no-repeat 10px -195px;}

.link{background: url(images/grey_btns.png) no-repeat 11px -262px;}
.link:hover{background: url(images/grey_btns.png) no-repeat 11px -327px;}
.link:active{background: #f9f9f9 url(images/grey_btns.png) no-repeat 11px -327px;}

.contact{background: url(images/grey_btns.png) no-repeat 10px -388px;}
.contact:hover{background: url(images/grey_btns.png) no-repeat 10px -453px;}
.contact:active{background: #f9f9f9 url(images/grey_btns.png) no-repeat 10px -453px;}


a.util_btn{
    color: #999999;
}


/* Page comments */
.dcomment{
    display: block;
    width: 553px;
    padding: 20px;
    border-bottom: 1px solid #dddddd;
}
.dcomment.start{
    background: #F8F8F8;
    min-height: 105px; /* added to match signed in size*/


}
.dcomment .dpic{
    float: left;
    width: 65px;
    height: 65px;
    border-radius: 4px;
    margin-right: 15px;
    overflow: hidden;
    background: url(images/fb_empty.jpg);
}
.dcomment .dpic img{
    float: left;
    width: 65px;
}
.fbcom{
    text-align: center;
    font-size: 18px;
    line-height: 20px;
    color: #666;
    height: 105px; /* added to match signed in size*/
}

.fbcomgood{
    text-align: center;
    font-size: 17px;
    line-height: 20px;
    color: #666;
    height: 89px;
    padding-top: 30px;
    text-shadow: 1px 1px white;
    background: url(images/check.png) no-repeat 273px 72px;
    background-color: #F3F9E3;
}

.fbcomdel{
    text-align: center;
    font-size: 17px;
    line-height: 20px;
    color: #666;
    height: 89px;
    padding-top: 30px;
    text-shadow: 1px 1px white;
    background: #eee url(images/check.png) no-repeat 273px 72px;
}

.fbcon{
    display: block;
    height: 29px;
    width: 128px;
    margin-left: auto;
    margin-right: auto;
    background: url(images/fb_cn.png);
}
.fbcom_sub{
    display: block;
    font-size: 14px;
    line-height: 16px;
}
.comname{
    font-size: 16px;
    line-height: 18px;
    color:#333;

}
.comname_det{
    font-size: 12px;
    line-height: 18px;
    color:#666;
    margin-left:10px; ;

}
.com_input{
    display: block;
    width: 441px;
    border: 1px solid #dddddd;
    font-size: 15px;
    line-height: 20px;
    padding-right: 15px;
    padding-left: 15px;
    resize: none;
    font-family: lato, sans-serif;
    color: #666666;
    padding-top: 7px;
    padding-bottom:0;
    height: 28px;

}
.acomment{
    float: right;
    width: 473px;
    color: #666;
    font-weight: 300;
    line-height: 23px;
    word-wrap: break-word;
}
a.com_subtle{
    color: #333;
}












/* BEGIN NEW Top Footer */
.footbase{
    width: 100%;
    float: left;
    z-index: -100;
    min-width: 1000px;
    background-color: #F6F5F2;
    border-top:1px solid #D1D1D1;
    border-bottom:1px solid #D1D1D1;
    margin-top: 38px;
}
.footbase a{
    text-decoration: none;
}
.footbase a:hover{
    text-decoration: underline;
}
.footbase .footmain {
    position: relative;
    margin: 0 auto;
    width: 972px;
    height: 407px;
    font-family: Lato , sans-serif;
    font-size: 13px;
    line-height: 34px;
    color: #666666;
    font-weight: 400;
    letter-spacing: 1px;
}
.footbase .footmain .footsec{
    margin: 0 auto;
    width: 972px;
    float:left;
}
.footbase .footmain .footfeatured {
    height: 80px;
}
.footbase .footmain .footfeatured .footnews {
    float: left;
    margin-top: 24px;
    height: 36px;
    width: 971px;
    background: url(images/news2.png) no-repeat;
}
.footbase .footmain .footlinks {
    margin-top: 20px;
    margin-bottom: 10px;
    height: 180px;
}
.footlinks .linkbox{
    float: left;
    -webkit-tap-highlight-color:transparent;
}
.linkbox a:active{
    background-color: transparent; !important;
}
.ft5{
    float: right;
    margin-top: 5px;
    margin-left: 18px;
    height: 152px;
    width: 163px;
    background: url(images/clk.png) no-repeat ;
}
.footlinks .linkbox a, .footcntry{
    display: block;
    color: #666666;
    letter-spacing: 0;
    font-size: 14px;
    line-height: 27px;
    font-weight: normal;
    font-family: Lato , sans-serif;
    font-weight: 300;
}
.footcntry{
    float: left;
    height: 16px;
    line-height: 16px;
    padding-left: 172px;
    margin-top: 22px;
    margin-bottom: 20px;
    background: url(images/nftflag.png) no-repeat;
}
.footsocial{
    float: right;
    line-height: 16px;
    margin-top: 20px;
}
.footdiv{
    float: left;
    width: 972px;
    height:2px;
    background-color: #cccccc;
    margin: 0;
    padding: 0;
}
.footcopy{
    float: right;
    font-size: 14px;
    line-height: 16px;
    margin-top: 20px;
    color:#999999;
    letter-spacing: 0;
    font-weight: 300;
    font-family: Lato , sans-serif;
}
.footcopy a{
    display: inline-block;
    color:#999999;
    font-size: 14px;
}


/* Lightbox */



/*--- Core Lightbox ---*/
.gfm_lb{
    position: absolute;
    top: 0;
    left: 0;
    right:0;
    bottom:0;
    width: 100%;
    height: 100%;
    z-index:10000;
    font-family: lato, sans-serif;
    font-weight: 300;
    /*overflow:hidden; */
    min-width:1000px;
}
.lb_overlay{
    position: fixed;
    height: 100%;
    width: 100%;
    top:0;
    bottom:0;
    right:0;
    left:0;
    background-color: #000000;
    opacity: 0.6;
    filter: alpha(opacity=60);
    -moz-opacity: 0.6;
    z-index: 1000;
}
.gfm_lb_outter{
    position: absolute;
    left: 50%;
}
.gfm_lb_main {
    position: relative;
    z-index:10001;
    left: -50%;
    margin: 50px auto 0;
    width: 484px;
    border-radius:16px;
    -moz-border-radius: 16px;
    -webkit-border-radius: 16px;
    border: 8px solid rgb(51, 51, 51);
    border: 8px solid rgba(51, 51, 51, .6);
    -webkit-background-clip: padding-box; /* for Safari */
    background-clip: padding-box; /* for IE9+, Firefox 4+, Opera, Chrome */
    overflow: hidden;
}
.gfm_lb_main.lb-medium {
    width: 640px;
}
.gfm_lb_main.lb-wide {
    width: 827px;
}
.gfm_lb_main .head{
    display: block;
    height: 88px;
    width: 100%;
    background: white url(images/gfm_logo.png) no-repeat 50%;
    border-bottom: 1px solid #DFE2D8;
}
.gfm_lb_main .head.with-title {
    background: #fff;
}
.lb-title {
    color: #5E7F08;
    font-size: 36px;
    font-weight: 400;
    margin: 0;
    position: relative;
    text-align: center;
    top: -20px;
    z-index: 0;
}
.gfm_lb_main .head .close{
    float: right;
    height: 20px;
    width: 20px;
    margin: 10px;
    background: url(images/close.png) no-repeat;
}
.gfm_lb_main.lb-medium .head .close,
.gfm_lb_main.lb-wide .head .close {
    margin-top: 14px;
    margin-right: 13px;
    position: relative;
    z-index: 20;
}
.gfm_lb_main .foot{
    display: block;
    width: 100%;
    background: white;
    border-top: 1px solid #DFE2D8;
    padding-bottom: 30px;
}
.gfm_lb_main .lrgbtn{
    display: block;
    margin-left: auto;
    margin-right: auto;
    padding: 12px 10px;
    width: 322px;
    height: 41px;
    border: 1px solid #e8e8e8;
    border-bottom: 3px solid #E3E3E3;
    background: white;
    font-weight: 500;
    text-decoration: none;
    font-size: 14px;
    line-height: 16px;
}
.gfm_lb_main .lrgbtn:hover{
    background: #FDFDFD;
}
.gfm_lb_main .lrgbtn:active{
    background: #F9F9F9;
    padding-top: 13px;
    height: 40px;
}
.gfm_lb_main .lrgbtn.nolink:hover{
    background: white;
}
.gfm_lb_main .lrgbtn.nolink:active{
    background: white;
    padding-top: 12px;
    height: 41px;
}

.gfm_lb_main .lrgbtn .l2{
    display: block;
    font-weight: 300;
    margin-top: 7px;
    color: #666;
    font-size: 14px;
    line-height: 16px;
}

.gfm_lb_main .dtxt{
    display: block;
    margin-right: auto;
    margin-left: auto;
    text-align: center;
    font-weight: 500;
    font-size: 36px;
    line-height: 38px;
    width: 440px;
}

/*For 2+ line title*/
.gfm_lb_main .longtitle {
    line-height:1.2;
}

.gfm_lb_main .dtxt2{
    display: block;
    text-align: center;
    font-size: 16px;
    line-height: 21px;
    color: #666;
    text-shadow: 1px 1px white;
    margin-top: 30px;
    margin-bottom: 30px;
}
.gfm_lb_main .dtxt2 a{
    color: #666;
}
.gfm_lb_main .dtxt2.lot{
    text-align: left;
    padding: 0 10px 10px;

}

.gfm_lb_main .campaignimg {
    width:200px;
    height:132px;
    border-radius:5px;
    overflow:hidden;
    margin:30px auto 0 auto;
}


.lbimg {
    width:200px;
    height:132px;
    border-radius:5px;
    overflow:hidden;
    margin:30px auto 10px auto ;
}

.lbimg img {
    width:200px;
    margin: 0 !important;
    height: auto !important;
}
a .lbimg img { border: none;}


.gfm_lb_main .fundlink {
    color:#5A8100;
    font-size:14px;
    display: block;
    text-decoration: none;

}

.fundlink2 {
    color:#666;
    font-size:14px;
    text-align: center;
}

.sharefb img{
    border-radius: 5px;
    height: 132px;
    margin: 30px auto 0;
    overflow: hidden;
    width: 200px;
    display: block;
    margin-bottom: 10px;
}


.gfm_lb_main .twittericon {
    width:19px;
    height:16px;
    background:url(images/twitter.jpg);
    display:inline-block;
    position:relative;
    right: 5px;
    top: 2px;
}

.gfm_lb_main .sharetwitter {
    font-size:16px;
    text-align:center;
    display:block;
    text-decoration:none;
}

.gfm_lb_main .friendsupport .text {
    font-size:20px;
    line-height:20px;
    font-weight:300;
    color:#333333;
}

.gfm_lb_main .friendsupport .fbprofile {
    width:65px;
    height:65px;
    border-radius:5px;
    background:url(images/fb_user.jpg);
    border:1px solid #DDDDDD;
    float:left;
    margin-bottom:30px;
}

.gfm_lb_main .foot .supporttxt {
    text-align:center;
    margin:30px auto 0 auto;
    font-size:16px;
    line-height:16px;
}

.gfm_lb_main textarea {
    width:318px;
    height:58px;
    border:0;
    font-size:15px;
    font-family:Lato, sans-serif;
    font-weight:400;
    padding:12px;
    border:1px solid #CCCCCC;
    border-radius:5px;
    resize: none;
    outline:none;
    margin:30px 0 20px 0;
    color:#999999;
}

.gfm_lb_main .campaignimg_small {
    width:100px;
    height:65px;
    overflow:hidden;
    border-radius:5px;
    float:left;
}

.gfm_lb_main .fundname {
    font-size:16px;
    line-height:16px;
    text-align:left;
}

.clwin{
    display: block;
    width: 213px;
    height: 37px;
    text-align: center;
    margin: 30px auto 0;
    font-size: 20px;
    line-height: 22px;
    font-family: montserrat, sans-serif;
    font-weight: 700;
    padding-top: 17px;
}
.clwinload{
    display: block;
    width: 211px;
    height: 35px;
    margin: 30px auto 0;
    padding-top: 17px;
    background:url(images/loading.gif) no-repeat 23px 15px;
    border: 1px solid #dddddd;
    border-radius: 5px;
}

a.clwin{
    color: white;
    text-decoration: none;
}
a.clwin:hover{
    color: white;
    text-decoration: none;
}






.con{
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 322px;
    /*height: 65px;*/
    border: 1px solid #e8e8e8;
    border-bottom: 3px solid #E3E3E3;
    background: white;
    font-size: 14px;
    line-height: 16px;
    padding: 15px;
}


.con .dpic{
    float: left;
    width: 65px;
    height: 65px;
    border-radius: 4px;
    margin-right: 15px;
    overflow: hidden;
}
.con .dpic img{
    float: left;
    width: 65px;
}
.con_to{


}
.con_name{
    font-size: 16px;
    line-height: 18px;
    font-weight: 300;
    margin-top: 7px;
    margin-bottom: 8px;
    word-break: break-all;

}
.con_fb{
    float: left;
    padding-left: 20px;
    color: #666666;
    background: url(images/fb_icon.jpg) no-repeat 0 0;
    font-size: 14px;
    line-height: 16px;

}










/*--- Reusable Signup LightBox Assets ---*/

.gfm_lb_main .body{

    text-align: center;
    color: #666666;
    font-size: 14px;
    line-height: 22px;

}
.gfm_lb_main .tm{
    color:#000;
    text-decoration: underline;
}
.gfm_lb_main .tm:hover{
    text-decoration: underline;
}

.su_form{
    display: block;
    width: 342px;
    margin: 20px auto -5px;
    text-align: left;
}
.su_form.terms{
    margin-top: -3px;
}
.su_form.terms2{
    margin-top: 27px;
}
.su_form .label{
    display: block;
    font-size: 16px;
    line-height: 18px;
    color:#333;
    text-shadow: 1px 1px #ffffff;
    margin-bottom: 8px;
    margin-left: 2px;
}
.su_form .label span{
    color: #666666;
}
.su_form .label.error, .label.error, .ziplabel.error{
    color: #b40000 !important;
}
.su_form .field{
    display: inline-block;
    width: 320px;
    padding: 8px 10px 8px 10px;
    margin: 0 0 17px 0;
    border-radius: 7px;
    border: 1px solid #cccccc;
    box-shadow: inset 1px 1px 2px #cccccc;
    font-size: 15px;
    line-height: 17px;
    color: #000000;
    font-family: lato, sans-serif;
    font-weight: 300;
    resize: none;
}
.su_form .field.error{
    background-color: #FFEAEC;
    border: 1px solid #FFBFBF;
}
.su_form .field.short{
    width: 144px;
}
.su_form .capt{
    float: right;
    width: 164px;
    height: 35px;
    overflow: hidden;
    padding: 0;
    margin: 0 0 7px 0;
    border-radius: 7px;
    border: 1px solid #cccccc;
    box-shadow: inset 1px 1px 2px #cccccc;
}
.su_form .field.help_txt{
    color: #666666;
    /*font-style: italic;*/
}
.su_form .field.act_txt{
    font-size: 15px;
    line-height: 17px;
    color: #000000;
    font-family: lato, sans-serif;
    font-weight: 300;
    font-style: normal;
}
a.linkb{
    color: #666;
}
.twitter_widget{
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 450px;
    height: 250px;
    background-color: #E0FFFF;
}

.shfb{
    display: block;
    margin-left: auto ;
    margin-right: auto ;
    width: 215px;
    height: 29px;
    background: url(images/sh.jpg) no-repeat 0 0;
}
.shfb:hover{background: url(images/sh.jpg) no-repeat 0 -29px;}
.shfb:active{background: url(images/sh.jpg) no-repeat 0 -58px;}

.shtw{
    display: block;
    margin-left: auto ;
    margin-right: auto ;
    width: 215px;
    height: 29px;
    background: url(images/sh.jpg) no-repeat 0 -87px;
}
.shtw:hover{background: url(images/sh.jpg) no-repeat 0 -116px;}
.shtw:active{background: url(images/sh.jpg) no-repeat 0 -145px;}

.never {
    text-align: center;
    height: 32px;
    font-size: 14px;
    line-height: 32px;
    color: #000000;
    background: url(images/underline.png) no-repeat scroll 154px 25px;
}

.folcamp{
    display: block;
    margin-left: auto ;
    margin-right: auto ;
    width: 276px;
    height: 54px;
    background: url(images/folcamp.jpg) no-repeat 0 0;
}
.folcamp:hover{background: url(images/folcamp.jpg) no-repeat 0 -54px;}
.folcamp:active{background: url(images/folcamp.jpg) no-repeat 0 -108px;}

.friendship{
    display: block;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 30px;
    width: 331px;
    height: 65px;
}
.dpic2{
    float: left;
    width: 65px;
    height: 65px;
    border-radius: 4px;
    overflow: hidden;
}
.dpic2 img{
    float: left;
    width: 65px;
}
.yesno{
    float: left;
    width: 171px;
    height: 65px;
    margin: 0 15px;
}
.yesno.yes{
    background: url(images/yesno.png) no-repeat 0 0;
}
.yesno.no{
    background: url(images/yesno.png) no-repeat 0 -65px;
}
.yesno.load{
    background: url(images/yesno.png) no-repeat 0 -129px;
}
.yesnoload{
    float: left;
    width: 24px;
    height: 24px;
    margin: 24px 0 0 73px;
    background: url(images/load.gif) no-repeat 0 0;

}



/* short donate with expand option */
.short_pop{
    display: block;
    width: 100%;
    height: 80px;
    padding-top: 30px;
    background: url(images/dash.png) repeat-x;
}
.sp_btn{
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 200px;
    height: 18px;
    font-size: 16px;
    line-height: 18px;
    padding: 10px 20px;
    border: 1px solid #DDDDDD;
    border-bottom: 3px solid #DDDDDD;
    text-align: center;
    font-weight: 300;
}
.short_pop a.sp_btn{
    color: #666;
}
.short_pop .sp_btn:hover{

}
.short_pop .sp_btn:active{
    box-shadow: inset 1px 1px 3px #f4f4f4;
    padding-top: 11px;
    height: 17px;

}
.shortdonate {
    display: none;
}
/* END short donate with expand option */

/* Branded Bar */
.branded{
    display: block;
    width: 972px;
    margin: 90px auto -75px;
    height: 50px;
    background: white;
    border: 1px solid #dddddd;
    font-weight: 300;
    font-family: lato, sans-serif;
    font-size: 16px;
    line-height: 50px;
    color:#333;

}
.branded a{text-decoration: none;}
.branded a:hover{text-decoration: underline;}

.branded .bdiv{
    float: left;
    margin: 10px 20px 10px 10px;
    width: 1px;
    height:30px;
    background: #dddddd;
}
.branded .bclose{
    float: right;
    height: 20px;
    width: 20px;
    margin: 15px 10px;
    background: url(images/close.png) no-repeat;
}
.branded .btext{
    float: left;
}
.branded .bpartner{
    float: left;
    margin-left:10px;
    height:40px;
    width:139px;
    margin-top:5px;
}
.branded .bpartner.chron{
    margin-top: 3px;
    width: 139px;
    height: 43px;
}
.branded .bpartner.carbridge{
    margin-top: 4px;
    width: 136px;
    height: 40px;
}


/* END  Branded Bar */


/*Image Sprite*/

.donationsprite {background: url(images/donate_sprite.png) no-repeat top left;
    display:inline-block;}
.donatesprite-alert{ background-position: 0 0; width: 16px; height: 15px;
    margin-right:5px; } 
.donatesprite-arrow{ background-position: 0 -17px; width: 59px; height: 23px;
        margin-left:10px;
 } 
.donatesprite-creditcard{ background-position: 0 -42px; width: 31px; height: 23px;
    display:inline-block;
    float:right;
    position:relative;
    top:15px;
    right:10px;
    } 
.donatesprite-creditcards{ background-position: 0 -67px; width: 217px; height: 49px;
    margin-top:64px;
    margin-left: 20px; } 
.donatesprite-dropdown{ background-position: 0 -118px; width: 10px; height: 7px; } 
.donatesprite-fb{ background-position: 0 -127px; width: 12px; height: 12px;
    margin-left:20px;
    position:relative;
    top:4px;
    } 
.donatesprite-fbcolor{ background-position: 0 -141px; width: 15px; height: 15px;
    margin-right:10px;
    position:relative;
    top:2px; } 
.donatesprite-lock{ background-position: 0 -158px; width: 12px; height: 16px;
    margin-right:5px; } 
.donatesprite-mail{ background-position: 0 -176px; width: 16px; height: 12px;
    margin-right:5px; } 
.donatesprite-check{ background-position: 0 -189px; width: 18px; height: 18px;
    margin-right:5px;
    float:left;
    margin-top:30px } 

/*End image sprite*/

hr {    border: 0;
    height: 0;
    border-top: 1px solid #dddddd;
    }    

.divider hr {margin:0px 30px;}


.detailscontainer {
    padding:0 20px;
}

.donationright {float:right;
    width:264px;}

/*Sidebar */

.photoframe{
    width: 264px;
    height: 175px;
    overflow: hidden;
    margin-top: 20px;

}
.photoframe img{
    width: 264px;
}

.processsidebar .campaignphoto {
    width:264px;
    height:175px;
    margin-top:20px;
    border-radius:5px;
}

.processsidebar {
    margin-top:20px;
    padding-bottom:10px;
    height:auto;
}

.processdonation {
    width:594px;
    border:1px solid #E8E8E8;
    margin:20px 0;
    border-radius:5px;
    float:left;}

.processdonation .amount {
    font-size:36px;
    font-weight:300;
    margin:30px;
    padding-bottom:20px;
    border-bottom:1px solid #E8E8E8;
    line-height: 36px;
}    

.processheader {font-family:Lato, sans-serif;
    font-weight:300;
    font-size:36px;
    color:#666666;
    margin:25px 30px 20px 30px;
    }

.processheader .headline {
    display:inline-block;
}    

/*end sidebar*/


/*donation flow*/
.donationbox {
    width:594px;
    height:157px;
}

.donationamount {
    background:#F3F9E4;
    height:153px;
    line-height:100px;
    width:314px;
    font-size:100px;
    color:#5F7E19;
    border-top:1px solid #E8E8E8;
    border-bottom:1px solid #E8E8E8;
    border-left:0;
    border-right:0;
    font-family: 'Montserrat', sans-serif;
    padding-left:10px;
    float:left;
    letter-spacing:-5px;
    text-align:right;
    outline:none;
}


.dollarsign {
    float:left;
    background:#F3F9E4;
    height:155px;
    width:58px;
    padding-left:30px;
    font-size:100px;
    color:#5F7E19;
    border-top:1px solid #E8E8E8;
    border-bottom:1px solid #E8E8E8;
    font-family: 'Montserrat', sans-serif;
    letter-spacing:-5px;
    line-height:157px;}

.zeros {
    float:left;
    background:#F3F9E4;
    height:155px;
    width:151px;
    font-size:100px;
    color:#5F7E19;
    border-top:1px solid #E8E8E8;
    border-bottom:1px solid #E8E8E8;
    font-family: 'Montserrat', sans-serif;
    letter-spacing:-5px;
    line-height:157px;
    text-align:right;
    padding-right:30px}

.donationerror {
    background:#FFEAEC !important;
    color:#B40000 !important;
}

.processdonation ::-webkit-input-placeholder {
   color:#5F7E19;
}

.processdonation :-moz-placeholder {
   color:#5F7E19;  
}

.processdonation ::-moz-placeholder {
   color:#5F7E19;  
}

.processdonation :-ms-input-placeholder {  
   color:#5F7E19;  
}

.donationform {
    float:left;
    padding-left:25px;
    width: 535px;
    }

.donationform input {
    /*height:46px;*/
    padding-top:11px;
    padding-bottom:11px;
    font-size:20px;
    font-weight:300;
    font-family:Lato, sans-serif;
    border:1px solid #CCCCCC;
    border-radius:5px;
    display:block;
    padding-left:11px;
    outline:none;}

/*Remove the IE x from form fields*/
input[type=text]::-ms-clear { display: none; }

/*fancy checkbox image replacement*/

.donationform input[type=checkbox].css-checkbox {
    display:none;
}

.donationform input[type=checkbox].css-checkbox + label.css-label {
padding-left:19px;
height:14px; 
display:inline-block;
line-height:14px;
background-repeat:no-repeat;
background-position: 0 0;
vertical-align:middle;
cursor:pointer;
font-size:13px;
font-weight:300;
margin-top:10px;
margin-bottom:0;
}

.donationform input[type=checkbox].css-checkbox:checked + label.css-label {
background-position: 0 -14px;
}
.donationform label.css-label {
background-image:url(images/checkboxes.png);
-webkit-touch-callout: none;
-webkit-user-select: none;
-khtml-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
}

/*end fancy checkbox image replacement*/


/*donation form elements*/

.donationform .dd_arrow {
    position:absolute;
    right: 14px;
    top: 21px;
    background:url(images/donate_sprite.png);
    background-position: 0 -118px;
    width: 10px;
    height: 7px;
}

.donationform .checkboxsecond {
    margin-right:5px;
    float:right;}

.donationform label, .donationform .label {
    display:block;
    font-family:Lato, sans-serif;
    font-weight:300;
    font-size:20px;
    color:#333333;
    margin-bottom:10px;
    margin-top:25px;
}    

.usefb {
    float:right;
    font-size:12px;
    color:#000000 !important;
    font-weight:300;
    text-transform:uppercase;
    position:relative;
    bottom:-5px;
}

.donationform ::-webkit-input-placeholder {
   color:#999999;
}

.donationform :-moz-placeholder {
   color:#999999;  
}

.donationform ::-moz-placeholder {
   color:#999999;  
}

.donationform :-ms-input-placeholder {  
   color:#999999;  
}

.placeholder {
    color:#999999;
}


.donationform .firstname {
    width:242px;
    float:left;
}

.donationform .lastname {
    width:242px;
    margin-left:20px;
    float:left;
}

.donationform .fullwidth {
    width:519px;
}

.donationform .creditcard, .donationform .fullname {
    margin-bottom:30px;
 }

.donationform .country{
    height:46px;
    width:242px;
    border:1px solid #CCCCCC;
    border-radius:5px;
    line-height:48px;
    padding-left:12px;
    font-size:20px;
    font-weight:300;
    font-family:Lato, sans-serif;
    float:left;
    position:relative;
}

.donationform .zip {
   /* height:46px;*/
    width:242px;
    border:1px solid #CCCCCC;
    border-radius:5px;
    padding-left:12px;
    font-size:20px;
    font-weight:300;
    font-family:Lato, sans-serif;
    float:right;
}
.donationform .zip.error, .donationform .country.error {
    background:#FFEAEC;
    color:#b40000 !important;
}

.donationform .filled {
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    -webkit-border-bottom-left-radius: 0px;
    -webkit-border-bottom-right-radius: 0px;
    -moz-border-radius-bottomleft: 0px;
    -moz-border-radius-bottomright: 0px;
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
}

.donationform .ziplabel {width:200px;}

.donationform .autocomplete {
    width:287px;
    border-left: 1px solid #CDCDCD;
    border-right: 1px solid #CDCDCD;
    border-bottom: 1px solid #CDCDCD;
    background:#ffffff;
    overflow:hidden;
    position:absolute;
    top:106px;
    font-size:17px;
    font-weight:300;
    color:#333333;
    -webkit-box-shadow: 2px 2px 3px 0px rgba(0,0,0,0.15);
    -moz-box-shadow: 2px 2px 3px 0px rgba(0,0,0,0.15);
    box-shadow: 2px 2px 3px 0px rgba(0,0,0,0.15);
}

.donationform .autocomplete a {
    height:35px;
    padding-left:12px;
    line-height:35px;
    display:block;
    width:287px;
    text-decoration:none;
    border-top: 1px solid #CDCDCD;
    color:#333333;
    overflow: hidden;
}

.donationform .autocomplete a:hover,
 {text-decoration:none;
}

.odd {background:#EEEEEE;}

.donationform .dropdown {
    z-index:10;
    width:254px;
    height:140px;
    overflow-x:hidden;
    overflow-y:scroll;
    border-left: 1px solid #CDCDCD;
    border-right: 1px solid #CDCDCD;
    border-bottom: 1px solid #CDCDCD;
    background:#ffffff;
    position:absolute;
    top:42px;
    left:-1px;
    font-size:17px;
    font-weight:300;
    color:#333333;
    -webkit-box-shadow: 2px 2px 3px 0px rgba(0,0,0,0.15);
    -moz-box-shadow: 2px 2px 3px 0px rgba(0,0,0,0.15);
    box-shadow: 2px 2px 3px 0px rgba(0,0,0,0.15);
}

.donationform .dropdown a {
    height:35px;
    padding-left:12px;
    line-height:35px;
    display:block;
    width:244px;
    text-decoration:none;
    border-top: 1px solid #CDCDCD;
    color:#333333;
}

.donationform .dropdown a:hover {text-decoration:none;}

.donationform .dropdown .odd {background:#EEEEEE;}

.donationform .zipcontainer {
    position:relative;
}

.donationform .month, .donationform .year {
    float:left;
    width:47px;
}

.donationform .ccslash {
    float:left;
    font-size:30px;
    line-height:48px;
    color:#666666;
    margin:0 10px;
}

.donationform .cvv {
    width:74px;
    display:inline-block;
    float:right;
    margin:0 0 30px 0;
}

.donationform .smallcopy {
    font-size:14px;
    font-family:Lato, sans-serif;
    font-weight:300;
    color:#333333;
}
a.drktxt {
    color:#666 !important;
}

.donationform .goback {
    font-size:14px;
    font-weight:500;
    margin:20px 0;
    display:block;
}

.donationform .security {
    width:244px;
    border:1px solid #CCCCCC;
    border-radius:5px;
    padding-left:12px;
    font-size:20px;
    font-weight:300;
    font-family:Lato, sans-serif;
    float:left;
}

.donationform .full {
    width: 596px;
    margin-left: -26px;}

.donationform .ccconfirm {
      font-family:Lato, sans-serif;
    font-weight:300;
    font-size:20px;
    color:#333333;
    line-height:20px;
    margin-bottom:30px;
}

.donationform .ccinfo {
    font-weight:300;
    font-size:16px;
    line-height:16px;
    margin-bottom:10px;
}

.ccedit {font-size:14px;
    color:#5F7E19;
    cursor:pointer;}

.donationform .small {
    font-size:13px;
    color:#999999 ;
    line-height: 13px;
    margin-top:10px;
}

.donationform .smalldescription {
    font-size:13px;
    color:#666666;
    margin-top:10px;
}

.donationform .smalldescription .bold {
    color:#000000;
}

.donationform input.error, .donationbox .error {
    background:#FFEAEC;
    color: #B40000 !important;
}
.donationform label.error, .processheader .error {
    color: #B40000;
}

.learnmore {
    float: right;
    margin-top:30px;
    margin-bottom:5px;
        font-family:Lato, sans-serif;
    font-size:14px;
}

.certifiedcharity {
    font-family:Lato, sans-serif;
    font-size:15px;
    float:left;
    margin-top:30px;
}

a.charitydesc {
    font-family:Lato, sans-serif;
    font-size:14px;
    color:#666666;
    line-height:1.5;
    margin:5px 0;
    display:block;
}

a.charitydesc.nohov {
    text-decoration: none;
}

.charitydesc {
    font-family:Lato, sans-serif;
    font-size:14px;
    color:#666666;
    line-height:1.5;
    margin:5px 0;
}

.charitydesc .bold {
    font-weight: bold;
}


/*Donation comment box*/

.donationcommentbox {
    width:532px;
    height:105px;
    border:1px solid #CCCCCC;
    border-radius:5px;
    margin-top:30px;
}

.donationcommentbox .photo {
    width:105px;
    height:100%;
    background:#F3F9E3;
    border-right:1px solid #CCCCCC;
    border-bottom-left-radius: 5px;
    border-top-left-radius: 5px;
    float:left;
}


.donationcommentbox .phototext {
    font-size:12px;
    font-weight:300;
    position: relative;
    top: 2px;
}

.donationcommentbox .comment textarea {
    width:398px;
    height:100%;
    float:left;
    border:0;
    font-size:20px;
    font-family:Lato, sans-serif;
    font-weight:300;
    padding:12px;
    resize: none;
    outline:none;
}

.donationcommentbox .fbprofile {
    width:65px;
    height:65px;
    border-radius:5px;
    background:url(images/fb_user.jpg);
    margin:10px auto 0 auto;
    border:1px solid #DDDDDD;
}

/*end donation comment box*/



/*buttons*/

.continuebtn {
    text-align: center;
    display: block;
    width: 264px;
    height: 47px;
    font-size: 28px;
    line-height: 30px;
    font-family: montserrat, sans-serif;
    font-weight: 700;
    border-radius:5px;
    padding-top: 17px;
    margin:30px auto;
    background-position:0 0;
    text-shadow: 1px 1px 1px rgba(153, 102, 1, 0.5);
    letter-spacing:-1px;
}
a.continuebtn{
    color: white;
    text-decoration: none;
}
a.continuebtn:hover{
    color: white;
    text-decoration: none;
    background-position:0 -64px;
}

a.continuebtn:active{
    background-position:0 -128px;}

.confirmbtn {
    text-align: center;
    margin-top: 30px;
    display: block;
    width: 264px;
    height: 47px;
    font-size: 28px;
    line-height: 30px;
    font-family: montserrat, sans-serif;
    font-weight: 700;
    border-radius:5px;
    padding-top: 17px;
    margin:70px auto 30px auto;
    background-position:0 0;
    text-shadow: 1px 1px 1px rgba(153, 102, 1, 0.5);
     letter-spacing:-1px;
}
a.confirmbtn{
    color: white;
    text-decoration: none;
}
a.confirmbtn:hover{
    color: white;
    text-decoration: none;
    background-position:0 -64px;
}

a.confirmbtn:active {
    background-position:0 -128px;
}

.fbconnect {
    margin-top: 25px;
    display: block;
    width: 296px;
    font-size: 26px;
    line-height: 30px;
    font-family: montserrat, sans-serif;
    height: 47px;
    font-weight: 300;
    letter-spacing:-1px;
    padding-top: 17px;
    margin:40px auto 15px auto;
    background:url(images/fb_cn_big.png);
    padding-left:91px;
    font-weight: bold;
}

.fbconnect.like {
    width: 268px;
    padding-left:119px;
}
.fbconnect.view {
    width: 268px;
    padding-left:119px;
}

a.fbconnect {
    color:#ffffff;
    text-decoration: none;
}

a.fbconnect:hover {
    color:#ffffff;
    text-decoration: none;
    background-position:0 -64px;
}

a.fbconnect:active {
    color:#ffffff;
    text-decoration: none;
    background-position:0 -128px;
}

.nextbutton {
    width:222px;
}

.sfrbutton {
    width:184px;
}

.sarbutton {
    width:248px;
    margin-left:auto;
    margin-right:auto;
}

.xsmallbtn {
    text-align: center;
    display: block;
    height: 29px;
    font-size: 13px;
    line-height: 29px;
    font-family: montserrat, sans-serif;
    text-transform: uppercase;
    font-weight: 700;
    border-radius:5px;
    background-position:0 0;

}
a.xsmallbtn{
    color: white;
    text-decoration: none;
}
a.xsmallbtn:hover{
    color: white;
    text-decoration: none;
    background-position:0 -29px;
}

a.xsmallbtn:active{
    background-position:0 -58px;}

.loadingbtn {
    display: block;
    width: 242px;
    height:42px;
    border:1px solid #CCCCCC;
    border-radius:5px;
    padding:10px;
    margin-left:auto;
    margin-right:auto;
}

.loadingbtn .loadinganim {
    width:100%;
    height:100%;
    border:1px solid #CCCCCC;
    background:url(images/loading.gif) no-repeat;
    background-position:50%;
}
.loadingbtn .frame {
    width:100%;
    height:100%;
    border:1px solid #CCCCCC;
}

.fbmedium {
    display: block;
    width: 185px;
    height: 54px;
    font-size: 20px;
    line-height: 54px;
    font-family: montserrat, sans-serif;
    font-weight: bold;
    letter-spacing:-1px;
    background:url(images/fb_medium.png);
    padding-left:91px;
    margin: 30px auto 0 auto;
}

a.fbmedium {
    color:#ffffff;
    text-decoration: none;
}

a.fbmedium:hover {
    color:#ffffff;
    text-decoration: none;
    background-position:0 -54px;
}

a.fbmedium:active {
    color:#ffffff;
    text-decoration: none;
    background-position:0 -108px;
}

.foot .fbmediumshare {
    padding-left: 79px;
    width:197px;
}
@media \0screen\,screen\9 { 
    .foot .fbmediumshare {
        padding-left: 69px\9;
        width: 207px\9;
    }
}

.twittershare {
    display: block;
    width: 180px;
    height: 54px;
    background:url(images/twittershare.png);
    padding-left:91px;
    margin: 30px auto 0 auto;
}

a.twittershare:hover {
    background-position:0 -54px;
}

a.twittershare:active {
    background-position:0 -108px;
}

.dashboardbtn {
    width:119px;
}

/*End buttons*/



/*About the organizer*/

.aboutorganizerheadline {
    width:306px;
    float:right;
    margin:30px 0 5px 0;
    font-family: montserrat, sans-serif;
    font-size:14px;
    color:#666666;
}

.aboutorganizer {
    float: right;
    width: 284px;
    padding: 10px;
    border: 1px solid #e8e8e8;
    border-bottom: 3px solid #E3E3E3;
    border-radius:5px;
    margin-bottom: 20px;
}

.aboutorganizer .img {
    float: left;
    width: 40px;
    height: 40px;
    border-radius: 4px;
    margin-right: 15px;
    overflow: hidden;
}

.aboutorganizer hr {margin:12px 0 10px 0;}

.aboutorganizer .smalltext {
    display:inline;
    font-size:12px;
    color:#666666;
    position: relative;
    top: 2px;
}

.aboutorganizer .mediumtext {
    font-size:13px;
    color:#666666;
}

.aboutorganizer .nofb {
    float: left;
    width: 40px;
    height: 40px;
    border-radius: 4px;
    margin-right: 15px;
    background:url(images/fb_user_40.jpg);
    border:1px solid #DDDDDD;
}
/*End about organizer*/

/*REWARDS!*/

.rewardcontainer {
    display:block;
    border-top:1px solid #e8e8e8;
    padding: 30px 0px 10px 10px;
    overflow:hidden;
}
.rewardcontainer.inmessager {
    border-top:none;
    padding: 30px 0px 10px 10px;
    overflow:hidden;
}

.reward {width:145px;
    padding: 10px 9px;
    border: 1px solid #e8e8e8;
    border-bottom: 3px solid #E3E3E3;
    border-radius:5px;
    float:left;
    margin-left:18px;
    margin-bottom:20px;
    word-wrap: break-word;
    }

.reward:hover, .rewardna:hover {
    border:1px solid #cccccc;
    border-bottom:3px solid #cccccc;
    background:#f5f5f5;
}    

.reward .title {font-size:14px;
    text-transform: uppercase;
    font-weight:400;
    text-align:center;
    background:#F3F9E7;
    border-radius:7px;
    padding:5px 0;
    color:#666666;
    display:block;
    }

.reward .rewardamount {font-size:40px;
    font-family: montserrat, sans-serif;
    text-align:center;
    margin-top:5px;
    margin-bottom:0;
    display:block;    
    }
.reward .rewardamount.inmess {font-size:40px;
    margin-top:18px;
}
.reward .rewardamount.inmess.smm, .reward .rewardamount.smm {font-size:35px;
    margin-top:18px;
}

.reward .description {font-size:14px;
    color:#666666;
    margin-top:10px;
    display:block;
    }
.reward .whatsleft {
    font-size:13px;
    color:#999999;
    margin-top:10px;
    padding-top: 3px;
    display:block;
    text-align: center;
    border-top: 1px solid #dddddd;
}
.reward .whatsleft.padd {

    padding-top: 10px;

}

.rewardna {
    width:145px;
    padding: 10px;
    border: 1px solid #e8e8e8;
    border-bottom: 3px solid #E3E3E3;
    border-radius:5px;
    float:left;
    margin-left:20px;
    margin-bottom:20px;
    background: #F6F6F6;
}

.rewardna .title {
font-size:14px;
    text-transform: uppercase;
    font-weight:400;
    text-align:center;
    background:#F3F9E7;
    border-radius:7px;
    padding:5px 0;
    color:#666666;
    display:block;
    background:#EBEBEB;
}

.rewardna .rewardamount {
font-size:40px;
    font-family: montserrat, sans-serif;
    text-align:center;
    margin-top:5px;
    margin-bottom:0;
    display:block;    
    color:#666666;
}


.rewardna .description {
    font-size:14px;
    color:#666666;
    margin-top:10px;
    display:block;
    word-wrap: break-word;
}

.rewardna:hover, .reward:hover {
    text-decoration:none !important;
}

/*end Rewards*/


/*Wish List*/

.wishlistcontainer {
    display:block;
    border-top:1px solid #e8e8e8;
    padding: 30px 0 0 10px;
    overflow:hidden;
}
.wishlistcontainer.inmessage {
    display:block;
    border-top:none;
    padding: 30px 0 10px 10px;
    overflow:hidden;;
}

.wishlist {
    width:145px;
    /*height:147px;*/
    padding: 10px 9px;
    padding-bottom: 15px;
    border: 1px solid #e8e8e8;
    border-bottom: 3px solid #E3E3E3;
    border-radius:5px;
    float:left;
    margin-left:18px;
    margin-bottom:20px;
}
.wishlist.wdon {width:145px;
    margin-left:203px;
    text-decoration: none;
}
.wishlist.wdon:hover {
    text-decoration: none;
}

.wishlist .title {font-size:14px;
    font-weight:400;
    text-align:center;
    background:#F3F9E7;
    border-radius:7px;
    padding:4px 0;
    display:block;
}

.wishlist .img {
    width:143px;
    height:95px;
    overflow:hidden;
    border-radius:5px;
    margin-top:10px;
    display:block;

}

.wishlist .description {font-size:14px;
    line-height:14px;
    color:#666666;
    margin-top:7px;
    font-weight: 400;
    display:block;
    word-wrap: break-word;
    }    

.wishlistna {
    background: #F6F6F6;
}

.wishlistna .title {
    color:#666666;
    background:#EBEBEB !important
}

.wishlistna .wishlistamount {
    color:#666666;
}

.wishlistna:hover, .reward:hover {
    text-decoration:none !important;
}

/*end Wish list*/



.greencontainer {
    background:#F3F9E4;
    padding: 25px 0 30px 0;
    border-top: 1px solid #dddddd;
    border-bottom: 1px solid #dddddd;
}

.greencontainer .headline {
    font-size:26px;
    margin-left:30px;
    margin-bottom:25px;
    font-weight:300;
}
.greencontainer .headline span{
    font-weight: 400;
}

.fbfriend {
    width:63px;
    height:63px;
    background:url(images/fb_user.jpg);
    display:inline-block;
    margin-left:27px;
    border-radius:4px;
    border:1px solid #DDDDDD;
}

.fbsmalltext {
    font-size:16px;
    font-weight:300;
    text-align:center;
    color: #666;
}

.skiptext{
    display: block;
    font-size:14px;
    line-height: 16px;
    font-weight:300;
    text-align:center;
    margin-top: 10px;
    color: #666;
}
a.skiptext {
    color: #666 !important;
}

.never2 {
    display: block;
    height: 32px;
    background: url("images/underline2.png") no-repeat scroll 150px 20px;
}

.processdonation .tyheadline {    
    font-size:36px;
    font-weight:300;
    margin:25px 30px 25px;
    line-height: 36px;}

.copypaste {font-family:Montserrat, sans-serif;
    font-size:14px;
    margin:35px 30px;
    color:#666666;}
.copypaste a {font-family:Lato, sans-serif;
    font-size:16px;}

.receiptemail {margin-top:35px;
    margin-bottom:35px;
    font-weight:300;
    font-size:16px;}
.receiptemail .donatesprite-mail {
    margin-left:30px;
}

.caringbridge-ty {
    margin:35px 30px 23px;
    color:#666;
}
.caringbridge-ty a,
.caringbridge-ty a img { border: none; }



.fullcontainer {
    margin-bottom:20px;
    border: 1px solid #dddddd;
    border-radius:5px;
    -webkit-box-shadow: 1px 2px 2px 0px rgba(0, 0, 0, 0.1);
    -moz-box-shadow:    1px 2px 2px 0px rgba(0, 0, 0, 0.1);
    box-shadow:         1px 2px 2px 0px rgba(0, 0, 0, 0.1);
}

.resultsnumber {
    float:right;
    font-weight:300;
    font-size:12px;
    font-family:"Lato", sans-serif;
}

.allresults {
    text-align:center;
    display:block;
    margin:0 auto 30px auto;
    width:248px;
    height:29px;
    line-height:29px;
    text-transform: uppercase;
    background:url(images/allresults.png);
}

.allresults:hover {
    background-position:0 -29px;
}

.allresults:active {
    background-position:0 -58px;
}

.relatedtext {
    text-align:center;
    font-size:16px;
    font-weight:300;
}

.tile {float: left;
width: 246px;
height: 281px;
border: 1px solid #d1d1d1;
border-radius: 6px;
background: #F6F5F2 no-repeat 215px 255px; /*this had url(images/stripe.png) but that image doesn't exist */
font-family: 'Montserrat', sans-serif;
font-size: 16px;
line-height: 18px;
font-weight: 400;
color: #333333;
box-shadow: 1px 1px 3px #CECECE;}

.tile .pho {
display: block;
width: 230px;
height: 152px;
padding: 8px 8px 0 8px;
overflow: hidden;
}

.tile .pho img {
width: 230px;
}
.tile a {
color: #333333;
text-decoration: none;
}
.tile .amt {
display: block;
width: 246px;
height: 49px;
font-size: 30px;
line-height: 49px;
text-align: center;
}

.tile .pro {
display: block;
width: 246px;
height: 8px;
border: 1px solid #e8e8e8;
background: #ffffff;
border-right: none;
border-left: none;
overflow: hidden;
}

.tile .fill{
    display: block;
    height: 10px;
    background: url('//www.gofundme.com/mvc/css/images14/fill.png');
}

.tile .title {
display: block;
width: 222px;
padding: 10px 8px 8px 8px;
padding-top: 10px;
white-space: nowrap;
overflow: hidden;
}

.tile .loc {
display: block;
width: 205px;
color: #666666;
padding: 0 8px;
font-size: 12px;
line-height: 14px;
font-family: 'Lato', sans-serif;
white-space: nowrap;
overflow: hidden;
}


/*Caring Bridge*/

.caringbridge {
    width:532px;
    height:131px;
    border:1px solid #CCCCCC;
    border-radius:5px;
    margin-bottom: 10px;
}

.caringbridge .cblogo {
    width:76px;
    height:97px;
    background:url(images/cb.jpg);
    margin:15px;
    float:left;
}

.caringbridge .title {
    font-size:20px;
    margin-top:15px;
    line-height:20px;
    color:#333333;
}

.caringbridge .desc {
    font-size:17px;
    font-weight:300;
    line-height:17px;
    margin-top:15px;
    margin-bottom:5px;
}

.caringbridge .cbform {
    width: 413px;
    float: left;
    margin-left: 7px;
}

.caringbridge .cbform {
    font-size:13px;
    color:#666666
}


.caringbridge .cbform  input {
    display:inline-block;
    height: 13px;
    margin-right:3px;
}

.ie8 .caringbridge .cbform  input {
    margin-right:1px;
}

.ie8 .donationform .addon input {
    padding-left:0;
}


.caringbridge .cbtextfield {
    width:46px;
    height:28px !important;
    margin-left:5px;
    font-size: 15px !important ;
    padding: 3px;
}

.caringbridge .sep {width:1px;
    height:109px;
    border-right:1px dotted #AAAAAA;
    float:left;
    margin-top:10px;}

.radiocb {
    border:0 !important;
    outline:0;
    display:inline-block;
    height: 20px !important;
    margin-right:3px;
}

.radiocb2 {
    margin-top: 2px !important;
    border:none !important;
    padding-left: 4px !important;
}

.cbfloat{
    float: left;
    vertical-align: top;
    height: 36px;
    line-height: 36px;
}
/*.cbfloat.cblow{
   margin-top: 11px;
}
*/



.givesum .t{
    color: #333333;
    font-size: 18px;
    line-height: 20px;
    margin: 25px 0 15px;
}
.givesum {
    color: #666;
    font-size: 16px;
    line-height: 20px;
    margin: 25px 0 15px;
}
.givesum .bol {
    color: #000;
}

.rad{
    border:0 !important;
    outline:0;
    float: left;

}
.givesum .cbanon{
    display: inline-block;
    font-size: 14px;
    line-height: 11px;
    color: #666666;
}
.ie8 .givesum .cbanon {
    line-height: 33px;
}


.cbanon{
    display: inline-block;
    font-size: 14px;
    line-height: 13px;
    color: #666666;
}

/*End Caring Bridge*/

/*Sharing bar */

.share_container {
    width: 342px;
    margin:0 auto;
}

.share_container .campaign_link {
    width:331px;
    padding-top:8px;
    padding-bottom:8px;
    font-size:15px;
    font-weight:400;
    font-family:Lato, sans-serif;
    border:1px solid #CCCCCC;
    border-radius:5px;
    display:block;
    padding-left:11px;
    outline:none;
    color:#666666;
}

.share_container .sharetxt {
    display: block;
    text-align: left;
    font-size: 16px;
    line-height: 21px;
    color: #666;
    font-weight:400;
    text-shadow: 1px 1px white;
    margin-top: 30px;
    margin-bottom: 10px;
}

.share_container .sharebar {
    width:322px;
    border-radius:5px;
    background:#ffffff;
    padding: 10px;
    border: 1px solid #e8e8e8;
    border-bottom: 3px solid #E3E3E3;
    overflow: hidden;
}

.share_container textarea {
    width:328px;
    height:53px;
    border:0;
    font-size:13px;
    font-family:Lato, sans-serif;
    font-weight:400;
    padding:7px;
    border:1px solid #CCCCCC;
    border-radius:5px;
    resize: none;
    outline:none;
    color:#666666;  
    margin:0;
}

.share_container .widgetpreview {
    width:250px;
    height:356px;
    background:#ffffff;
    border-radius:5px;
    margin-left:auto;
    margin-right:auto;
   border:1px solid #CCCCCC;
}

/*Header search dropdown*/

.hps1 {float: left;
width: 100%;
position:relative;
}

.hps1 .box {margin: 0 auto;
width: 972px;}

.hps1 .box .left {float: left;
width: 227px;}
.lil_drop {
    width:227px;
    position: absolute;
    left: 144px;
    top: 0px;
    border: 1px solid #E8E8E8;
    background: white;
    z-index: 1;
    box-shadow: 5px 5px 5px #999999;
    }
.lil_suggest {
    float:left;
    /*height: 33px;*/
    width:197px;
    padding: 8px 15px;
    font-size: 13px;
    line-height: 18px;
    color: #666666;
    background: white;
    /*white-space: nowrap;*/
    /*overflow: hidden;*/
    font-family: lato, sans-serif;
}
.lil_suggest.locsug {
width:227px;
}

.lil_suggest:hover, .lil_suggest.hover {background:#eeeeee;}

.lil_drop a {text-decoration:none;}

/*End Header search dropdown*/

/*Navigation icons*/

.campaignsign {
    width:330px;
    height:417px;
    background:white;
    margin-left: auto;
    margin-right: auto;
}

.cat_drop {
    display:block;
    width:380px;
    height:auto;
    background:#ffffff;
    border:1px solid #E8E8E8;
    box-shadow:5px 5px 5px #999999;
    padding:15px 0 15px 15px;
    position:absolute;
    left:375px;
}

.cat_drop .cat_item {
    width:140px;
    height:42px;
    line-height:42px;
    display:block;
    padding-left:39px;
    text-decoration: none;
    font-family:Lato, sans-serif;
    color:#666666;
}

.cat_drop .cat_item:hover {
    text-decoration:underline;
}

.col1, .col2 {width:190px;
    float:left;}

.c1{background: url("images/cat_nav_icons.png") no-repeat 0 -24px;}
.c2{background: url("images/cat_nav_icons.png") no-repeat 0 -68px;}
.c3{background: url("images/cat_nav_icons.png") no-repeat 0 -110px;}
.c4{background: url("images/cat_nav_icons.png") no-repeat 0 -151px;}
.c5{background: url("images/cat_nav_icons.png") no-repeat 0 -196px;}
.c6{background: url("images/cat_nav_icons.png") no-repeat 0 -237px;}
.c7{background: url("images/cat_nav_icons.png") no-repeat 0 -278px;}
.c8{background: url("images/cat_nav_icons.png") no-repeat 0 -320px;}
.c9{background: url("images/cat_nav_icons.png") no-repeat 0 -363px;}
.c10{background: url("images/cat_nav_icons.png") no-repeat 0 -404px;}
.c11{background: url("images/cat_nav_icons.png") no-repeat 0 -448px;}
.c12{background: url("images/cat_nav_icons.png") no-repeat 0 -489px;}
.c13{background: url("images/cat_nav_icons.png") no-repeat 0 -529px;}
.c14{background: url("images/cat_nav_icons.png") no-repeat 0 -574px;}
.c15{background: url("images/cat_nav_icons.png") no-repeat 0 -615px;}
.c16{background: url("images/cat_nav_icons.png") no-repeat 0 -655px;}
.c17{background: url("images/cat_nav_icons.png") no-repeat 0 -698px;}
.c18{background: url("images/cat_nav_icons.png") no-repeat 0 -776px;}
.c19{background: url("images/cat_nav_icons.png") no-repeat 0 -738px;}
.c23{background: url("images/cat_nav_icons.png") no-repeat 0 16px;}

/*End navigation icons*/

.fbusername{
    overflow: hidden;
    word-break: break-all;
    max-height: 40px;
    font-size: 16px;
    line-height: 16px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.fbusername.sm{
    font-size: 13px;
    width: 225px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}


@media only screen
and (min-device-width : 768px)
and (max-device-width : 1024px) {
    .donationamount {
        height:97px;
        line-height: 105px;
        width:325px;
        padding-left:0;
        float:left;
        outline:none;
        border-radius:0;
        padding-right: 0;
        margin-right: 0;
        padding-top:28px;
    }
    .pagetitle {
        line-height:54px;

    }
    input[type="text"] {
    -webkit-appearance: caret;
    -moz-appearance: caret;
    }
    .donationform .firstname, .donationform .lastname, .donationform .zip {
        width:232px;
    }
    .donationform .fullwidth{
        width:507px;
    }
    .donationform .country {
        height:46px;
    }
    .caringbridge .cbtextfield{
        top:0;
    }
}







</style>
    <div id="carousel-299058" class="carousel slide">
        <ol class="carousel-indicators">
            <li data-target="#carousel-299058" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-299058" data-slide-to="1"></li>
            <li data-target="#carousel-299058" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="item active"> <img class="img-responsive" src="images/carouselthumb.jpg" alt="thumb" />
                <div class="carousel-caption"> Carousel caption. Here goes slide description. Lorem ipsum dolor set amet. </div>
            </div>
            <div class="item"> <img class="img-responsive" src="images/carouselthumb.jpg" alt="thumb" />
                <div class="carousel-caption"> Carousel caption 2. Here goes slide description. Lorem ipsum dolor set amet. </div>
            </div>
            <div class="item"> <img class="img-responsive" src="images/carouselthumb.jpg" alt="thumb" />
                <div class="carousel-caption"> Carousel caption 3. Here goes slide description. Lorem ipsum dolor set amet. </div>
            </div>
        </div>
        <a class="left carousel-control" href="#carousel-299058" data-slide="prev"><span class="icon-prev"></span></a> <a class="right carousel-control" href="#carousel-299058" data-slide="next"><span class="icon-next"></span></a> </div>
<!-- InstanceEndEditable -->


<!-- InstanceBeginEditable name="EditRegion4" -->
<h3>Store catalog <small>Subtext for header</small></h3>
<!-- InstanceEndEditable -->

<div class="row">
<div class="col-lg-12">
<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="a">
    <div class="pagetitle txt1 ">5027 to FIRST Robotics Champs!</div>



        <div class="fundphoto round5">
                                        <img src="https://2dbdd5116ffa30a49aa8-c03f075f8191fb4e60e74b907071aee8.ssl.cf1.rackcdn.com/4071692_1428425620.7497.jpg">
                        </div>
    <div class="details round5">
    <div class="headbar">
                            <a href="http://www.gofundme.com/mvc.php?route=search&amp;term=95111&amp;country=US" class="loc mr10" title="Browse pages near SAN JOSE, CA." style="max-width: 159px;">
                SAN JOSE, CA            </a>
                            <a href="http://www.gofundme.com/Sports-Teams-Clubs/" class="cat">SPORTS</a>
                <div style="clear: both;"></div>
        <div class="raised ">
            <span class="cur ">$</span>21,775<span class="of"> of </span><span class="goal">$15k</span>
        </div>
        <div class="bar">
            <div class="fill" style="width: 100%;"></div>
        </div>
        <div class="time " title="">
            Raised by <span>273</span> people in <span>6</span> days        </div>
        <a href="#" class="donatebtn round5 btn1 donateButton">Donate Now</a>
                    <a href="#" class="fbs mt20 share-fb" data-pc="16_fb_px">Share on Facebook</a>
            <a href="http://www.gofundme.com" class="delogo"></a>
            </div>
</div>
<div style="clear: both;"></div>
    
<!-- Share bar -->
<div id="top-share-bar" class="sharebar mt30 round5" data-total_shares="556" data-facebook_pre="0" data-facebook_count="525" data-twitter_count="31">
        <div class="big">556</div>
        <div class="biglbl">TOTAL<br> SHARES</div>
        <div class="div"></div>
        <!--todo add fb pc codes-->
        <a href="#" class="fbshare mt10 share-fb" data-pc="16_fb_px"></a>
        <div class="count ml20">525</div>
        <div class="div"></div>
        <a href="#" class="twshare mt10 share-tw" data-pc="14_tw_2"></a>
        <div class="count ml13">31</div>
</div>
<!-- END Share bar -->
    <!-- campaign organizer -->
<div class="createdby mt30 round5">
        <div class="cbimg">

                <img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpf1/v/t1.0-1/p320x320/10891761_794113523958864_1642152287785191307_n.jpg?oh=5d26075588570feac4acc58020e80cf4&amp;oe=559A9E97&amp;__gda__=1436897649_04d8d685b902f3601a030ada90c0c224">
        </div>
        <div class="cbdate">
                Created April 7, 2015        </div>
        <div class="cbname  ">
                <a href="#" class="cbmail openContactLb" title="Contact the organizer of this campaign."></a>
                Tina Nguyen        </div>
</div>
<div style="clear: both;"></div>    <div class="donate_left mt30">
        

        <div class="left_bx round5">
                <div class="section_head">
                        UPDATE #1                        <div class="fr">13 HOURS AGO</div>
                </div>

                <div class="update_content">

                        We want to express our deepest gratitude to our 250+ donors! When we first got back from our regional competition, we weren't sure how we were going to obtain the money to go to Worlds and so we planned to take a very limited amount of people. Thanks to you guys, we're able to comfortably take a few more students and mentors along with us! Furthermore, all excess funds raised will go towards materials, tools, and other things that will support our robotics team for years to come (we especially need to get that workshop set up!). <br>
<br>
Again, thank you for all the kind words, the donations, and the shares! We couldn't have done it without you!
                        
                        <div class="shareopts mt30">
                                <a href="#" class="txt1 subscribeUpdates">Subscribe to Updates</a>
                                                                <a href="#" class="smdtn fr donateButton" style="float: right;"></a>
                                <a href="#" class="twshare mr10 share-tw" data-pc="14_tw_up" style="float: right;"></a>
                                <a href="#" class="fbshare mr10 share-fb" data-pc="14_fb_up" style="float: right;"></a>
                        </div>
                </div>
        </div>

        <div id="allUpdates" style="display: none;float:left;">
                        </div>

<!-- Page Message -->
<div class="pg_msg ">
        We are members from Andrew Hill High School's FIRST Robotics Team 5027, located in San Jose, California, and we have qualified to the FIRST Worlds Championship. We are a second year rookie team that would love to take this opportunity. However we lack the funding for lodging and transportation to St. Louis, Missouri.&nbsp;&nbsp;As our school is located in a low-income neighborhood, we&nbsp;know that the students' families will not be able to afford the costs.<br><br>As our team is very new and fairly small, World Championships will help our team grow. Going to this event will also allow us to give our younger members something to aim for in future years and will open doors that will allow us to expand the program within our community and we hope you will support us along our journey!<br><br>Even if you cannot make a donation, please share this page with your friends and family!<br><br>For more information about FIRST and the FIRST Robotics Competition, visit here:<br><a href="http://www.usfirst.org/roboticsprograms/frc" target="_blank"> </a><a href="http://www.usfirst.org/roboticsprograms/frc" target="_blank" class="txt1" rel="nofollow">http://www.usfirst.org/roboticsprograms/frc</a><br><br>For more information about our team, contact&nbsp;ahhs.robotics@gmail.com</div>
<!-- END Page Message -->
                        <!-- Second Share Bar -->
<div id="bottom-share-bar" class="sharebar s2 mt30 round5">
        <div class="big">556</div>
        <div class="biglbl">TOTAL<br> SHARES</div>
        <a href="#" class="fbshare mt10 ml60 share-fb" data-pc="16_fb_px"></a>
        <div class="div2"></div>
        <a href="#" class="twshare mt10 share-tw" data-pc="14_tw_3"></a>
        <div class="div2"></div>
        <a href="#" class="smdtn mt10 donateButton"></a>
        <div class="dash mt10 mb20"></div>
        <div class="pglink mb10">COPY, PASTE &amp; SHARE:
                <span class="txt1">http://www.gofundme.com/r7dab3g</span>
        </div>
</div>
<!-- END Second Share Bar -->

<!-- Page Utilities -->
<a href="http://www.gofundme.com/" class="left_bx util mt30 round5" style="font-size: 14px;">
        <span class="whatis">What is GoFundMe?</span>Fundraising made easy &gt;&gt;</a>
<a href="#" class="left_bx util_btn mt30 round5 contact openContactLb">CONTACT</a>
<a href="#" class="left_bx util_btn mt30 round5 link widgetLb">LINK</a>
<a href="#" class="left_bx util_btn mt30 round5 poster printSignLb">POSTER</a>
<a href="#" class="left_bx util_btn mt30 round5 report openContactLb">REPORT</a>
<!-- END Page Utilities -->
            <div class="left_bx round5" id="commentBox">
        <div class="section_head">
            3 COMMENTS        </div>
        <div class="dcomment fbcomgood fadeOutMessage" style="display: none;" id="commentPostedMessage">
            Thanks! Your comment has been posted.
        </div>
        <div class="dcomment fbcomdel fadeOutMessage" style="display: none;" id="commentRemovedMessage">
            Your comment has been removed.
        </div>
                <div class="dcomment fbcom" id="leaveCommentBox">
            Please use Facebook to leave a comment below:
            <a href="#" class="fbcon mt20 mb20 doFbComment"></a>
            <span class="fbcom_sub"><u>Nothing</u> gets posted to your wall. Only your Facebook name &amp; photo are used.</span>
        </div>
                <div class="dcomment start" style="display: none;" id="commentInputBox">
            <div class="dpic">
                <img class="fb-photo" src="">
            </div>
            <div class="comname">
                <span class="fb_name"></span> <span class="comname_det">(Not <span class="fb_first_name"></span>? <a href="#" class="txt1 commentFbLogout">Sign Out</a>) </span>

                <form action="//www.gofundme.com/mvc.php?route=donate/postcomment" method="post" id="commentForm">
                    <textarea class="com_input round5 mt10 blink blink-check" name="Comments[text]" title="Type your comment here..." id="commentTextBox" autocomplete="off" style="overflow: hidden; word-wrap: break-word; height: 28px;">Type your comment here...</textarea>
                    <input type="hidden" name="FBLogin[uid]">
                    <input type="hidden" name="FBLogin[token]">
                    <input type="hidden" name="Comments[firstname]" class="fb_name_val">
                    <input type="hidden" name="Comments[fund_id]" value="4071692">
                    <a href="#" class="sub mt10 submitComment"></a>
                </form>
            </div>
            <div style="clear: both;"></div>
        </div>
                    <div class="comments-container">
            <div class="dcomment singleComment">
    <div class="dpic">
                    <img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xfa1/v/t1.0-1/p320x320/11051844_10153217370243055_4980012779434917278_n.jpg?oh=de13f153c7c2db58bf2ab88fb8277d61&amp;oe=55A028E3&amp;__gda__=1436721619_dfe36cb4e3997f147a849cf006088fe1">
            </div>
    <div class="comname">Citlali Yoloztin        <span class="comname_det"> 14 mins ago
            <span id="commentId_14024057" style="display: none;">
                |
                <a href="#" class="com_subtle txt1 deleteCommentToggle" data-id="14024057">
                    Delete
                </a>
            </span>
            <span id="cConfirmId_14024057" style="display: none;">
                | Are you sure?
                <a href="#" class="com_subtle txt1 confirmDeleteComment" data-id="14024057">
                    Yes
                </a> or
                <a href="#" class="com_subtle txt1 deleteCommentToggle" data-id="14024057">
                    Cancel
                </a>
            </span>
        </span>
        <div class="acomment mt10">
            I am happy to see this surpass the goal! I donated a few days ago before when it was approx only at $4k, and I come back just 3 days later &amp; it's over the goal. Fantastic- hope you kids take all your club members and/or use the extra funds to support your robotics club next year!        </div>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="dcomment singleComment">
    <div class="dpic">
                    <img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xfa1/v/t1.0-1/p320x320/10358571_10153562298384428_359656102343222633_n.jpg?oh=883fb80c2f5aca9bf95722dff1683513&amp;oe=55B08E6D&amp;__gda__=1440794913_49d46894d6afdf5f68917aebd1cb9a6d">
            </div>
    <div class="comname">Janice Montalvo        <span class="comname_det"> 16 hours ago
            <span id="commentId_13992783" style="display: none;">
                |
                <a href="#" class="com_subtle txt1 deleteCommentToggle" data-id="13992783">
                    Delete
                </a>
            </span>
            <span id="cConfirmId_13992783" style="display: none;">
                | Are you sure?
                <a href="#" class="com_subtle txt1 confirmDeleteComment" data-id="13992783">
                    Yes
                </a> or
                <a href="#" class="com_subtle txt1 deleteCommentToggle" data-id="13992783">
                    Cancel
                </a>
            </span>
        </span>
        <div class="acomment mt10">
            I have 2 kids that graduated from A.Hill and went on to college. Im glad to see so much support from the community. Go get them!        </div>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="dcomment singleComment">
    <div class="dpic">
                    <img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/p320x320/18114_10207183589174266_1840507794887569158_n.jpg?oh=21557bbd82a244f3e682fe3a0819151b&amp;oe=55AD21F2&amp;__gda__=1440986419_5a80106a31ef195aa648e0655157d91f">
            </div>
    <div class="comname">Vickie Roberts        <span class="comname_det"> 1 day ago
            <span id="commentId_13958548" style="display: none;">
                |
                <a href="#" class="com_subtle txt1 deleteCommentToggle" data-id="13958548">
                    Delete
                </a>
            </span>
            <span id="cConfirmId_13958548" style="display: none;">
                | Are you sure?
                <a href="#" class="com_subtle txt1 confirmDeleteComment" data-id="13958548">
                    Yes
                </a> or
                <a href="#" class="com_subtle txt1 deleteCommentToggle" data-id="13958548">
                    Cancel
                </a>
            </span>
        </span>
        <div class="acomment mt10">
            Good luck from Vickie Roberts,  Class of 1990.        </div>
    </div>
    <div style="clear: both;"></div>
</div>
            </div>
                            <div class="donerscroll">
                                    <a href="#" class="lr pleft" style="visibility: hidden;"></a>
                                                    <a href="#" class="lr pright" style="visibility: hidden;"></a>
                                <span>1-3</span> of
                <span>3</span> comments            </div>
            </div>
<script>
    $(function () {
        $('#commentTextBox').autosize({append: ''});
    });
    </script>    </div>
    
<!-- Right column of donate under created by -->
<div class="donate_right">
    <!-- Recent Donors-->
    <div class="right_bx mt30 round5">
                    <div class="section_head">
                273 DONATIONS                <a href="#" class="toggleoff txt1 donationToggle">RECENT</a>
                <div class="toggle" style="display: none;">
                                            <a href="#" class="txt1 donationToggle" rel="nofollow">RECENT</a>
                        <div class="togbk"></div>
                        <a href="#" class="txt1 donationsPage" data-index="0" rel="nofollow" data-type="highest">HIGHEST</a>
                                    </div>
                <input id="donationSort" type="hidden" value="recent">
            </div>
                            <div class="doner">
                                                            <div class="ddeat">
                        <div class="damt txt1">
                            <span>$</span>100</div>
                                                    <div class="dname">Sandra bernabe</div>
                                                <div class="dtime">
                            3 mins ago                         </div>
                    </div>
                                            <div class="dcom">Alumni class of 81 go Falcons</div>
                                                        </div>
                            <div class="doner">
                                                            <div class="ddeat">
                        <div class="damt txt1">
                            <span>$</span>25</div>
                                                    <div class="dname">John Arviola</div>
                                                <div class="dtime">
                            47 mins ago                         </div>
                    </div>
                                            <div class="dcom">Good Luck! </div>
                                                        </div>
                            <div class="doner">
                                                            <div class="ddeat">
                        <div class="damt txt1">
                            <span>$</span>100</div>
                                                    <div class="dname">Anonymous</div>
                                                <div class="dtime">
                            1 hour ago                         </div>
                    </div>
                                                        </div>
                            <div class="doner">
                                                            <div class="ddeat">
                        <div class="damt txt1">
                            <span>$</span>50</div>
                                                    <div class="dname">Lisa Pham</div>
                                                <div class="dtime">
                            1 hour ago                         </div>
                    </div>
                                            <div class="dcom">Good luck! You all have earned it!</div>
                                                        </div>
                            <div class="doner">
                                                            <div class="ddeat">
                        <div class="damt txt1">
                            <span>$</span>10</div>
                                                    <div class="dname">Anonymous</div>
                                                <div class="dtime">
                            2 hours ago                         </div>
                    </div>
                                                        </div>
                            <div class="doner">
                                                            <div class="ddeat">
                        <div class="damt txt1">
                            <span>$</span>50</div>
                                                    <div class="dname">Marcia Bacher</div>
                                                <div class="dtime">
                            2 hours ago                         </div>
                    </div>
                                                        </div>
                            <div class="doner">
                                                            <div class="ddeat">
                        <div class="damt txt1">
                            <span>$</span>20</div>
                                                    <div class="dname">Anonymous</div>
                                                <div class="dtime">
                            3 hours ago                         </div>
                    </div>
                                                        </div>
                            <div class="doner">
                                                            <div class="ddeat">
                        <div class="damt txt1">
                            <span>$</span>50</div>
                                                    <div class="dname">Robert Simmons </div>
                                                <div class="dtime">
                            4 hours ago                         </div>
                    </div>
                                                        </div>
                            <div class="doner">
                                                            <div class="ddeat">
                        <div class="damt txt1">
                            <span>$</span>50</div>
                                                    <div class="dname">Nick Bossi</div>
                                                <div class="dtime">
                            9 hours ago                         </div>
                    </div>
                                            <div class="dcom">I will always support the sciences whenever possible and you all have an amazing opportunity here and it sure sounds like you've earned it. Good luck! Have fun!</div>
                                                        </div>
                            <div class="doner">
                                                            <div class="ddeat">
                        <div class="damt txt1">
                            <span>$</span>500</div>
                                                    <div class="dname">Anonymous</div>
                                                <div class="dtime">
                            9 hours ago                         </div>
                    </div>
                                                        </div>
                        <div class="donerscroll">
                                    <a class="lr pleft" href="#" style="visibility: hidden;"></a>
                                                    <a href="#" class="lr pright donationsPage" data-index="10" title="Show the Next 10 Donations" rel="nofollow"></a>
                                <span>1-10</span> of
                <span>273</span> donations            </div>
            </div>
    <a href="http://www.gofundme.com/" class="right_bx mt30 round5 gfm_ad"></a>
</div>
<div style="clear: both;"></div>    <div class="afooter">
        <div class="aftxt mt30">Bring your <a href="http://www.gofundme.com/fundraising-ideas/">fundraising
                ideas</a> to life with an <a href="http://www.gofundme.com/">online donation website</a>
            from GoFundMe!
        </div>
        <a href="http://www.gofundme.com/" class="ogo"></a>
    </div>
</div>
<!-- InstanceEndEditable -->
</div>
</div>


</div><!-- middle col -->

<?php include('inc_featured.php'); ?>

</div><!-- / inner .row -->
</div>
</section>

<section class="custom-footer">
<?php include('inc_footer.php'); ?>
</section>
</div>
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="assets/js/jquery.js" type="text/javascript"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="assets/js/bootstrap.js"></script>
<!-- InstanceBeginEditable name="EditRegionJS" -->

<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>