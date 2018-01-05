*********************************************************
 *  Font Styles
 *********************************************************
h1 {
	text-align: center;
	font-family: 'Verdana';
	font-size: 26px;
	color: #104871;
	font-weight:bold;}
	/*
h2{	
	font-family: 'Verdana';
	font-size: 18;
	font-weight:bold;
	font-style: italic;
	text-decoration: underline;
	font-variant: small-caps;
}
h3{
	font-family: 'Verdana';
	font-size: 16px;
	font-weight:bold;
	color: yellow;
}
*/
th{
	color:#104871;
}

font.primary {
  color: #104871;/*<?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
*/
  font-size: 16;/*<?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
*/
  font-family: verdana, helvetica, arial;/*<?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
*/
}
font.alt1 {

 
  color: #104871;/*#ffffff;/*<?php echo H(OBIB_ALT1_FONT_COLOR);?>;
*/
  font-size: 17px;/*<?php echo H(OBIB_ALT1_FONT_SIZE);?>px;
*/
  font-family: verdana, helvetica, arial;/*<?php echo H(OBIB_ALT1_FONT_FACE);?>;
*/
}
font.alt1tab {
  color:#104871;/* #ffffff;/*<?php echo H(OBIB_ALT1_FONT_COLOR);?>;
*/
  font-size: 17px;/*<?php echo H(OBIB_ALT2_FONT_SIZE);?>px;
*/
  font-family:verdana, helvetica, arial;/* <?php echo H(OBIB_ALT2_FONT_FACE);?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
*/
  font-weight: bold;
/*<?php } else { ?>
  font-weight: normal;
<?php } ?>*/
}
font.alt2 {
  color: #000000;/*<?php echo H(OBIB_ALT2_FONT_COLOR);?>;
*/
  font-size: 17px/*<?php echo H(OBIB_ALT2_FONT_SIZE);?>px;
*/
 /font-family: verdana, helvetica, arial;/* <?php echo H(OBIB_ALT2_FONT_FACE);?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
*/
  font-weight: bold;
/*<?php } else { ?>
  font-weight: normal;
<?php } ?>
*/
}/*
font.error {
  color: <?php echo H(OBIB_PRIMARY_ERROR_COLOR);?>;
  font-size: <?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  font-weight: bold;
}
*/
font.small {
  font-size: 14px;
  font-family: verdana, helvetica, arial;/*<?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
*/
}

/*********************************************************
 *  Link Styles
 *********************************************************/
a.nav {
  /*color:#ffffff;/* <?php echo H(OBIB_ALT1_FONT_COLOR);?>;*/
  font-size: 10px;
  font-family: verdana, helvetica, arial;/*<?php echo H(OBIB_ALT1_FONT_FACE);?>;*/
  text-decoration: none;
  background-color: #000000;/*#2d6582;/*<?php echo H(OBIB_ALT1_BG);?>;*/
  border-style: solid;
  border-color:#000000;/* <?php echo H(OBIB_BORDER_COLOR);?>;*/
  border-width: 1;/*<?php echo H(OBIB_BORDER_WIDTH);?>*/

}

a:link {
  color: #104871;/* <?php echo H(OBIB_PRIMARY_LINK_COLOR);?>; */ /*Linkfarben*/
  /*text-decoration: none;*/
}
a:visited {
  color:#104871;/* <?php echo H(OBIB_PRIMARY_LINK_COLOR);?>;*/
}
a.primary:link {
  color:#104871;/* <?php echo H(OBIB_PRIMARY_LINK_COLOR);?>;
*/
}
a.primary:visited {
  color: #104871;/*<?php echo H(OBIB_PRIMARY_LINK_COLOR);?>;
*/
}
a.alt1:link { 
  text-decoration: none;			/*links hover in menue linke seite*/
  color:#104781;/*#ffffff;/* <?php echo H(OBIB_ALT1_LINK_COLOR);?>;*/
  

	
}

a.alt1:visited {
  color:#104781;/*#ffffff;/* <?php echo H(OBIB_ALT1_LINK_COLOR);?>*/
}
a.alt1:hover{
	
	text-decoration:underline;
}
a.alt2:link {
  color: #104781;/*<?php echo H(OBIB_ALT2_LINK_COLOR);?>;*/
  text-decoration:none;

}
a.alt2:visited {
  color: #104781;/*<?php echo H(OBIB_ALT2_LINK_COLOR);?>;*/
  text-decoration:none;
}
a.tab{	
	/*outline-radius:8px;						/*Reiter*/
	/*outline-style: outset;
	outline-color: #708090;/*#F8F8FF;*/
	/*background-color:#2d6582;*/
	text-decoration:none;
	
}
/*******************************************************************
*  Navigationsberreich
********************************************************************/
#navbar_top{
	height:25px;
}
.tab2 {
	border-radius:8px;
	color: #676AAD;
	/*background-color:#C5DDE9;
	font-weight: bold
	width: 150px;
	font-family: verdana, helvetica, arial;
	/*border-style: solid;
	border-width: thin;
	/*border-color: grey;
	border-color-radius:8px;*/
	/*outline-style: outset;
	/*outline-color: #708090;/*#F8F8FF;*/
	width:150px;
	text-align:center;
	height: 25px;
}
.tab1{
	/*border-collapse: separate;*/
	border-radius:8px;
	color: #9393A0;
	background-color:#C5DDE9;
	font-weight: bold
	width: 150px;
	font-family: verdana, helvetica, arial;
	width:150px;
	text-align:center;
	border-style: solid;
	border-width: thin;
	border-color: gray;
	height:25px;
}
a.tab1{							/*Reiter*/
	/*outline-style: inset;

	outline-color: #708090;/*#F8F8FF;*/
	/*background-color:#2d6582;*/
	text-decoration:none;	
}
.tab2   {
	border-width:150px;
	border-radius:8px;
	color: #104781;
	border-style: solid;
	border-width: thin;
	border-color: gray;
	outline-color: #708090;/*#F8F8FF;*/
	background-color:#C5DDE9;
}
/*#gab{
	background-color:gray;
}
.leftnavbar1{
	background-color:gray;
}*/
.devider{
	background-color:#104781;
	/*border-style:inset;*/
	
}	
a.tab:link {
  color: #104781 ;/*<?php echo H(OBIB_ALT2_LINK_COLOR);?>;
*/
  font-size: /*<?php echo H(OBIB_ALT2_FONT_SIZE);?>*/ 17px;
  font-family:verdana, helvetica, arial;/* <?php echo H(OBIB_ALT2_FONT_FACE);?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
*/
  /*font-weight: bold;/*
<?php } else { ?>
  font-weight: normal;
<?php } ?>
*/
  text-decoration: none
}
a.tab:visited {
  color:#104781;/*<?php echo H(OBIB_ALT2_LINK_COLOR);?>;
*/
  font-size:verdana, helvetica, arial;/* <?php echo H(OBIB_ALT2_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_ALT2_FONT_FACE);?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
*/
  font-weight: bold;
/*<?php } else { ?>
  font-weight: normal;
<?php } ?>
*/
  text-decoration: none
}
a.tab:hover {
	text-decoration: underline;
	color:#104781;; 
}
td.alt1 {
 /*background-color:#2d6582/*<?php echo H(OBIB_ALT1_BG);?>;
*/
  /*color: #ffffff;/* <?php echo H(OBIB_ALT1_FONT_COLOR);?>;
*/
  /*font-size: 17px;/*<?php echo H(OBIB_ALT1_FONT_SIZE);?>px;
*/
  /*font-family: verdana, helvetica, arial;/*<?php echo H(OBIB_ALT1_FONT_FACE);?>;
*/
  padding: 2;/*<?php echo H(OBIB_PADDING);?>;
*/
  border-style: solid;
  border-color: #000000;/*<?php echo H(OBIB_BORDER_COLOR);?>;
*/
  border-width: 1px/*<?php echo H(OBIB_BORDER_WIDTH);?>*/

}

td.tab1 {
  /*background-color:<?php echo H(OBIB_ALT1_BG);?>;*/
  
  /*color: #ffffff;/*<?php echo H(OBIB_ALT1_FONT_COLOR);?>;
*/
  font-size: 17px;/*<?php echo H(OBIB_ALT1_FONT_SIZE);?>px;
*/
  font-family: verdana, helvetica, arial;/*<?php echo H(OBIB_ALT2_FONT_FACE);?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
*/
  font-weight: bold;
/*<?php } else { ?>
  font-weight: normal;
<?php } ?>
*/
  padding: 2;/*<?php echo H(OBIB_PADDING);?>;*/
  /*outline-color: #708090;/*#F8F8FF;*/

}
font.alt1{
	position:absolute;
	top:300px;
}
td.tab2 {
  /*background-color: #c5dde9;/*<?php echo H(OBIB_ALT2_BG);?>;*/
  color:#000000;/* <?php echo H(OBIB_ALT2_FONT_COLOR);?>;*/
  font-size:17px;/* <?php echo H(OBIB_ALT2_FONT_SIZE);?>px;*/
  font-family: verdana, helvetica, arial;/*<?php echo H(OBIB_ALT2_FONT_FACE);?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>*/
  font-weight: bold;
/*<?php } else { ?>
  font-weight: normal;
<?php } ?>
*/
  padding: 2;/*<?php echo H(OBIB_PADDING);?>;*/
  /*outline-color: #708090;/*#F8F8FF;*/
}
#leftnavbar1{
	width:15px;
}
.leftnavbar2{
	/*text-align: center;*/
	width: 200px;
	
}
#leftnavborder_right{
	background-color: #000000;
	width: 1 ;
	//left 200px;
	
}
#leftnavbar2{
	width: 200px;
}
/*********************************************************
 *  Table Styles
 *********************************************************/
table.primary {
 /* border-collapse: collapse*/
}
table.border {
  border-style: solid;
  border-color:gray;		/* <?php echo H(OBIB_BORDER_COLOR);?>;
*/
  border-width: 	1px;		/*<?php echo H(OBIB_BORDER_WIDTH);?>
*/
}
th {
 /*background-color:#c5dde9	/* <?php echo H(OBIB_ALT2_BG);?>;
  color: <?php echo H(OBIB_ALT2_FONT_COLOR);?>;
*/
  font-size: 17;		/*<?php echo H(OBIB_ALT2_FONT_SIZE);?>px;
*/
  font-family: verdana, helvetica, arial;/*<?php echo H(OBIB_ALT2_FONT_FACE);?>;
*/
  padding: 2px/*<?php echo H(OBIB_PADDING);?>;
*/
  border-style: solid;
/*<?php if (OBIB_ALT2_FONT_BOLD) { ?>
*/
  font-weight: bold;
/*<?php } else { ?>
*/
  font-weight: normal;
/*<?php } ?>
*/
  border-color: #000000/*<?php echo H(OBIB_BORDER_COLOR);?>;
*/
  border-width: 1px;/* <?php echo H(OBIB_BORDER_WIDTH);?>;
 /* height: 1*/

}
th.rpt {
  background-color: #ffffff;/*<?php echo H(OBIB_PRIMARY_BG);?>;
*/
  color: #104871;/*<?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
*/
  font-size: 16px/*<?php echo (OBIB_PRIMARY_FONT_SIZE - 2);?>px;
*/
  font-family: verdana, helvetica, arial;/*Arial;
*/
  font-weight: bold;
  padding: 2px;/*<?php echo H(OBIB_PADDING);?>;
*/
  border-style: solid;
  border-color:#000000;/*<?php echo H(OBIB_BORDER_COLOR);?>;
*/
  border-width: 1;
  text-align: left;
  vertical-align: bottom;
}
td.primary {
  background-color: #ffffff;/*<?php echo H(OBIB_PRIMARY_BG);?>;
*/
  color: #104871;/*<?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
*/
  font-size: 16px;/*<?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
*/
  font-family: verdana, helvetica, arial;/*<?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
*/
  padding:2/* <?php echo H(OBIB_PADDING);?>;
*/
  border-style: solid;
  border-color: #000000;/*<?php echo H(OBIB_BORDER_COLOR);?>;
*/
  border-width: 1;/*<?php echo H(OBIB_BORDER_WIDTH);?>
*/
}
td.borderless {
  background-color: #ffffff;/*<?php echo H(OBIB_PRIMARY_BG);?>;
*/
  color: #104871;/*<?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
*/
  font-size: 16px;/*<?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
*/
  font-family:  verdana, helvetica, arial;/*<?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
*/
  padding: 2;/*<?php echo H(OBIB_PADDING);?>;
*/
}
td.rpt {
  background-color: #ffffff;/*<?php echo H(OBIB_PRIMARY_BG);?>;
*/
  color: #104871;/*<?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
*/
  font-size: 16px;/*<?php echo (OBIB_PRIMARY_FONT_SIZE - 2);?>px;
*/
  font-family:verdana, helvetica, arial;/*Arial;
*/
  padding:2;/* <?php echo H(OBIB_PADDING);?>;
*/
  border-top-style: none;
  border-bottom-style: none;
  border-left-style: solid;
  border-left-color:  #000000;/*<?php echo H(OBIB_BORDER_COLOR);?>;
*/
  border-left-width: 1;
  border-right-style: solid;
  border-right-color:  #000000;/*<?php echo H(OBIB_BORDER_COLOR);?>;
*/
  border-right-width: 1;
  text-align: left;
  vertical-align: top;
}
td.primaryNoWrap {
  background-color:#ffffff;/* <?php echo H(OBIB_PRIMARY_BG);?>;
*/
  color: #104871;/*<?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
*/
  font-size:16px;/* <?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
*/
  font-family: verdana, helvetica, arial;/*<?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
*/
  padding: 2;/*<?php echo H(OBIB_PADDING);?>;
*/
  border-style: solid;
  border-color: #000000<?php echo H(OBIB_BORDER_COLOR);?>;
  border-width: 1;/*<?php echo H(OBIB_BORDER_WIDTH);?>;
*/
  white-space: nowrap;
}
div.deviderVertical{
	width:100%;
	border-top-style: solid;
	border-color: rgb(197, 221, 233);
}
td.positionCategory{
	margin-left: -50px;
}

td.noborder {
  background-color: #ffffff;/*<?php echo H(OBIB_PRIMARY_BG);?>;
*/
  color: #104871;/*<?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
*/
  font-size:16px;/* <?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
*/
  font-family:verdana, helvetica, arial;/* <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
*/
  padding: 2px;/*<?php echo H(OBIB_PADDING);?>;
*/
}

table.form { margin-bottom: 1em }


/**************************************************************************
* 				Title
****************************************************************************/
td.title {
  background-color: #ffffff;/*<?php echo H(OBIB_TITLE_BG);?>;
*/
  color: #104871/*<?php echo H(OBIB_TITLE_FONT_COLOR);?>;
*/
  font-size:26px;/*<?php echo H(OBIB_TITLE_FONT_SIZE);?>px;
*/
  font-family: verdana, helvetica, arial;/*<?php echo H(OBIB_TITLE_FONT_FACE);?>;
*/
  padding: 2;/*<?php echo H(OBIB_PADDING);?>;
<?php if (OBIB_TITLE_FONT_BOLD) { ?>
*/
  font-weight: bold;
/*<?php } else { ?>
  font-weight: normal;
<?php } ?>
*/
  border-color:#000000;/* <?php echo H(OBIB_BORDER_COLOR);?>;
*/
  border-width: 1;/*<?php echo H(OBIB_BORDER_WIDTH);?>;
*/
  text-align: center;/*<?php echo H(OBIB_TITLE_ALIGN);;?>
*/
}

table.form th.title {
  background-color:#ffffff;/* <?php echo H(OBIB_PRIMARY_BG);?>;
*/
  color: #104871;/*<?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
*/
  text-align: center;
  font-weight: bold;
  font-size: 18px;
  border: none;
  border-bottom: solid /*<?php echo H(OBIB_ALT2_BG);?>*/ 2px;
}
.title{
	
	text-align: center;
	font-family: 'Verdana';
	font-size: 26px;
	color: #104871;
	font-weight:bold;
}
table.form th {
  text-align: right;
  vertical-align: top;
  background-color:#ffffff;/* <?php echo H(OBIB_PRIMARY_BG);?>;
*/
  color:#104871;/* <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
*/
  /*border: none;
*/
}
table.form .error { font-weight: bold; color: red }
table.form .error { font-weight: bold; color: red }


	
}
a.leftnavbar{
	color:#ffffff;
}
tbody {
	hight: 25px;
}
#leftnavborder_right{
	border-color: #104871;
	border:1;
}
/*********************************************************
 *  Form Styles
 *********************************************************/
form{
    border-style: solid;
    border-width: thin;
    border-color: grey;
    margin-top: 10px;
    margin-left: 10px;
    width:80%;
    padding: 5px;
}
input[type=text]{
	width:300px;
	display: block;
	}
.textarea{
	rows:4;
	cols:40;
	display: block;
}
select{
	width:300px;
	display: block;
	}
#neuer_nutzer{
	width: 80%;
	margin-top: 0px;
	margin-left:10px;
}
/*form.search{
	width:100px;
}*/
.search select{
	width: 100px;
}
.search a{
	font-family: 'Verdana';
	font-size: 18;
	font-weight:bold;
	font-style: italic;
	text-decoration: none;
	font-variant: small-caps;
	

}
.search a:hover{
	text-decoration: underline;
}
fieldset{
	color:#104871;
}
.search fieldset a{
	color:#104871;
	text-decoration: none;
	font-size: 16;
} 
.search fieldset a:hover{
	text-decoration: underline;
}
form.search{
	width:700px;
}
#isbn_search{
		/*margin-left: 310px;
		margin-top:	-23px;
		z-index:-2;*/
		//background-color:#1E90FF;
		
	}
/*
input.button {
  background-color: <?php echo H(OBIB_ALT1_BG);?>;
  border-color: <?php echo H(OBIB_ALT1_BG);?>;
  border-left-color: <?php echo H(OBIB_ALT1_BG);?>;
  border-top-color: <?php echo H(OBIB_ALT1_BG);?>;
  border-bottom-color: <?php echo H(OBIB_ALT1_BG);?>;
  border-right-color: <?php echo H(OBIB_ALT1_BG);?>;
  padding: <?php echo H(OBIB_PADDING);?>;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  color: <?php echo H(OBIB_ALT1_FONT_COLOR);?>;
}
input.navbutton {					/*bezeichnet den Logout button in der Nabvar*//*
  background-color: <?php echo H(OBIB_ALT2_BG);?>;
  border-color: <?php echo H(OBIB_ALT2_BG);?>;
  border-left-color: <?php echo H(OBIB_ALT2_BG);?>;
  border-top-color: <?php echo H(OBIB_ALT2_BG);?>;
  border-bottom-color: <?php echo H(OBIB_ALT2_BG);?>;
  border-right-color: <?php echo H(OBIB_ALT2_BG);?>;
  padding: <?php echo H(OBIB_PADDING);?>;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  color: <?php echo H(OBIB_ALT2_FONT_COLOR);?>;
	
}
input {
  background-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-left-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-top-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-bottom-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-right-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  padding: 0px;
  scrollbar-base-color: <?php echo H(OBIB_ALT1_BG);?>;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  color: <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
}
textarea {
  background-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-left-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-top-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-bottom-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-right-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  padding: 0px;
  scrollbar-base-color: <?php echo H(OBIB_ALT1_BG);?>;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  color: <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
}/*
select {
  background-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-left-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-top-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-bottom-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-right-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  padding: 0px;
  scrollbar-base-color: <?php echo H(OBIB_ALT1_BG);?>;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  color: <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
}*/

ul.nav_main { list-style-type: none; padding-left: 0; margin-left: 0; }
li.nav_selected:before { white-space: pre-wrap; content: "\bb  " }
ul.nav_main li.nav_selected { font-weight: bold }
ul.nav_sub li.nav_selected { font-weight: bold }
ul.nav_main li { font-weight: normal }
ul.nav_sub li { font-weight: normal }

li.report_category { margin-bottom: 1em }

table.results {
  width: 100%;
  border-collapse: collapse;
}
table.resultshead {
  width: 100%;
  border-collapse: separate;
  border-top: solid #c5dde9; /*<?php echo OBIB_ALT2_BG;?>*/ 3px;
  border-bottom: solid #c5dde9 /*<?php echo OBIB_ALT2_BG;?>*/ 3px;
  clear: both;
}
table.resultshead th {
  text-align: left;
  color: #104871;/*<?php echo OBIB_PRIMARY_FONT_COLOR;?>;
*/
  border: none;
  background: #ffffff;/*<?php echo OBIB_PRIMARY_BG;?>;
*/
  font-size: 16px;
  font-weight: bold;
  vertical-align: middle;
  padding: 2px;
}
table.resultshead td {
  text-align: right;
}
table.results td.primary { border-top: none; }
/*
table.buttons {
  margin: 0 0 0 auto;
  padding: 0;
  border-collapse: separate;
  background: white;
}
table.buttons td {
  background-color: <?php echo OBIB_ALT2_BG;?>;
  /* Hide from IE5/Mac \*//*
  border-color: <?php echo OBIB_ALT2_BG;?>;
  border-style: outset;
  border-width: 1px;
  /* End hiding *//*
  padding: 4px;
  font-weight: bold;
  font-size: 12px;
  text-align: center;
  vertical-align: middle;
}
table.buttons input {
  border: none;
  color: <?php echo OBIB_ALT2_FONT_COLOR;?>;
  background: <?php echo OBIB_ALT2_BG;?>;
  padding: 0;
  margin: 0;
  font-weight: bold;
  white-space: normal;
}
*/

table.buttons input hover { text-decoration: underline; }  
table.buttons a {
  color: #000000;/*<?php echo OBIB_ALT2_FONT_COLOR;?>;
*/
  text-decoration: none;
}
table.buttons a:hover { text-decoration: underline; }
table.buttons a:visited { color: #000000; /*<?php echo OBIB_ALT2_FONT_COLOR;?>; */}

div.errorbox {
  border-style: solid;
  border-color: #000000;/*<?php echo H(OBIB_BORDER_COLOR);?>;
*/
  border-width:1px;/* <?php echo H(OBIB_BORDER_WIDTH);?>;
*/
  max-width: 500px;
  margin: 10px;
  padding: 5px;
  background-color: #2d6582; /*<?php echo H(OBIB_ALT1_BG);?>;
  
*/
}
/**********************FEHLER***********************************/

div.errorbox .errorhdr { font-size: large; font-weight: bold }
div.errorbox ul { margin-left: 0; padding-left: 1.5em }
div.errorbox li { margin-left: 0 }

.error
	{
	font-family: verdana, helvetica, arial;
	font-size: 16px;
	/*text-decoration: blink;*/
	color: #ff1111;
	font-weight: bold;
	}
a.error
	{
	font-family: verdana, helvetica, arial;
	font-size: 16px;
	text-decoration: underline;
	color: #ff1111;
	font-weight: bold;
	}
/******************ACHTUNG**********************/

.attention
	{
	font-family: verdana, helvetica, arial;
	font-size: 16px;
	
	color: #FFBF00;
	font-weight: #FF8000;
	}

a.attention
	{
	font-family: verdana, helvetica, arial;
	font-size: 16px;
	text-decoration: underline;
	color: #FFBF00;
	font-weight: bold;
	}

/********************Bestätigungen***************/

.ok
	{
	font-family: verdana, helvetica, arial;
	font-size: 16px;
	
	color: #008000;
	font-weight: bold;
	}


/*Information, dass es sich um Testversion handelt*/

.notify
{
  text-align: center;
  font-family: 'Verdana';
  font-size: 18px;
  color: #104871;
  font-weight:bold;
}