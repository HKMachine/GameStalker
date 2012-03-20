<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Game Stalker</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<script type="text/javascript" src="public/js/jquery.js"></script>
		<script type="text/javascript" src="public/js/ui/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="public/js/ui/jquery-ui-1.8.17.custom.min.js"></script>
		<script type="text/javascript" src="public/js/navi.js"></script>
		<script type="text/javascript" src="public/js/plugins/isotope/isotope.js"></script>
		<script type="text/javascript" src="public/js/plugins/isotope/centeredMasonry.js"></script>
		<link type="text/css" href="public/css/dark-hive/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
		<link type="text/css" href="public/css/isotope/isotope.css" rel="stylesheet" />
		<script type="text/javascript">
		// needs to seperate jquery ui and isotope, and make them work with $.noconfict(function(){})
		
					$(function() {	
						function getPSN(){
							$.ajax({
								type:'GET',
								url:'proxyService/psn',
								dataType:'json',
								success: function(data){
									console.log(data);
									$('#Pavatar').empty();
									var img = "<img style=\"width:100%;height:100%\" id=\"theImg\" src=\""+data.AvatarMedium+"\" />";
									$('#Pavatar').prepend(img);
									var imgs = '<div><img id="palt" class="trophy" src="public/imgs/trophies/platinum.png"/></div><div><img id="gold" class="trophy" src="public/imgs/trophies/gold.png"/></div><div><img id="silver" class="trophy" src="public/imgs/trophies/silver.png"/></div><div><img id="bronze" class="trophy" src="public/imgs/trophies/bronze.png"/></div>';
									$('#trophies').empty().append(imgs);
									$('#plat').parent().append(data.Trophies.Platinum);
									$('#gold').parent().append(data.Trophies.Gold);
									$('#silver').parent().append(data.Trophies.Silver);
									$('#bronze').parent().append(data.Trophies.Bronze);
								}
							});
						}
						function getxbox(){
		$.ajax({
			type:'GET',
			url:'proxyService/xbox',
			dataType:'json',
			success: function(data){
				$('#xavatar').empty();
				var img = "<img style=\"width:100%;height:100%\" id=\"theImg\" src=\""+data.avatar.body+"\" />";
				$('#xavatar').prepend(img);
				$('#score').empty().append('<p>'+data.gamerscore+'</p>')
			}
		});
	}
		var $container = $('#isoParent');
						$container.isotope({
							itemSelector : '.item',
							layoutMode: 'masonry',
							masonry:{
								columnWidth: 60,
								gutterWidth:10,
							},
							filter: '.main'
						},ranLay());
			function getthird(){
				console.log($('#CB').width());
				return Math.floor($('#CB').width()/3.08);
			}
			function addPlats(){
				getPSN();
				getxbox();
				$('#login').hide();
				$.each($('.filter'),function(){
					$(this).show();
				});
				$('.house').show();
				$('.ops').show();
				$('.logout').show();
				$container.isotope({filter:'.home'},ranLay());	
			}
			
		function validate(info){
            $.ajax({
            type:'POST',
            url:'login/run',
            data: $.param(info),
            success: function(data){
            	console.log(data);
            	if(!isNaN(parseInt(data))){
            		$('#loginD').dialog("close");
            		$('#ux').hide();
            		$('#px').hide();
            		addPlats();
            	}
            	else{
            		$('#ux').show("explode",50);
            		$('#px').show("explode",50);
            		//disable button on animation becasue of trolls
            		$(":button:contains('Login')").attr("disabled","disabled");
            		$('#loginD').stop().effect("shake", {times : 3}, 100,function(){
            			$(":button:contains('Login')").removeAttr("disabled","disabled");
            		});
            	}
            }
            });
           }
           function ranLay(){
          $container.isotope({sortBy: 'random'});
          }
          function growglow(){
           $.each($('.contentBox'),function(){
           var w = $(this).width();
           	var h = $(this).height();
            $(this).hover(
           	function(){
           	$(this).css('cursor', 'pointer');
           	//make this with animate
           	$(this).stop(true,true).animate({
           		width: '+=5',
           		height: '+=5'
           	}, 200);
           	var color = $(this).css('color');
           	$(this).css("text-shadow", "0px 0px 15px " + color);
           	},
           	function(){
           		$(this).stop(true,true).animate({
           		width: '-=5',
           		height: '-=5'
           	},200);
           	$(this).css("text-shadow","");	
           	}
           	);
           	});
           }
	growglow();
	
	// Register validator under construction
	function Regvalidate(Obj){
		//disable reg button
		$(":button:contains('Register')").attr("disabled","disabled").addClass("ui-state-disabled");
		$('#pgBar').progressbar('value', 0);
		checkUsername(Obj, true);
		// re enables the Register Button
	}
	function enableReg(){
		      $(":button:contains('Register')").removeAttr("disabled").removeClass("ui-state-disabled");
	}
	function prgBar(){
		if(!$('#pg').length){
		$('.ui-dialog-buttonpane').prepend("<div id=\"pgBar\"style=\"width:270px;float:left;top: 4px;position: relative;\"></div>");
		$('#pgBar').progressbar({
			value:0
		});
		}
	}
	function checkUsername(user, validate){
		var obj ={};
		obj.username = user.username;
		if(user.username.length < 6){
			$('#nux').show();
			enableReg();
			return false;
		}
		$.ajax({
			type:'POST',
			url:'login/username',
			data:$.param(obj),
			dataType:'text',
			success:function(data){
				console.log(data);
				if(data == 'false'){
					$('#nux').hide();
				}
				else{
					$('#nux').show();
					enableReg();
					return false;
				}
				validate?$('#pgBar').progressbar('value', 10):false;
				if(validate){
					console.log('validating .. ');
					$('#pgBar').progressbar('value', 15);
					if(checkPass()){
						console.log('password good');
						$('#pgBar').progressbar('value', 25);
						if(checkEmail()){
							console.log('email good');
							$('#pgBar').progressbar('value', 40);
							var psn = user.psn!=""?true:false;
							var xbox = user.xbox!=""?true:false;
							if(!psn && !xbox){
								$('#psnx').show();
								$('#xboxx').show();
								enableReg();
								return false;
							}
							if(psn){
								console.log('checking psn');
							checkPsnTag(user, xbox);
							}
							else{
								checkXboxTag(user);
							}
						}
						else{
							enableReg();
							return false;
						}
					}
					else{
						enableReg();
						return false;
					}
				}
			}
		});
	}
	function resetOnClose(){
		$('#pgBar').remove();
		//reset fields
	}
	function checkPsnTag(tag, xbox){
		var obj = {};
		obj.tag = tag.psn;
		$.ajax({
			type:'POST',
			url:'proxyService/psn',
			data:$.param(obj),
			dataType:'json',
			success: function(data){
				if(data == 'exists'){
					$('#psnx').show();
					enableReg();
					return false;
				}
				if($.isEmptyObject(data)){
					$('#psnx').show();
					enableReg();
					return false;
				}
				else{
					$('#psnx').hide();
					$('#pgBar').progressbar('value', 70);
					if(xbox){
						console.log('checking xbox');
						checkXboxTag(tag);
					}
					else{
						userAdd(tag);
					}
				}
			}
		});
	}
	function userAdd(user){
		$.ajax({
			type:'POST',
			url:'login/add',
			dataType: 'text',
			data:$.param(user),
			success: function(data){
				console.log(data);
				$('#pgBar').progressbar('value', 100);
				enableReg();
			}
		})
	}
	function checkXboxTag(tag){
		var obj = {};
		obj.tag = tag.xbox;
		console.log(tag.xbox);
		$.ajax({
			type:'POST',
			url:'proxyService/xbox',
			data: $.param(obj),
			dataType:'text',
			success: function(data){
				console.log(data);
				if(data == 'exists'){
					$('#xboxx').show();
					enableReg();
					return false;
				}
				if($.isEmptyObject(data)){
					$('#xboxx').show(); 
					enableReg();
					return false;
				}
				/*
				 * ADD CONDITION FOR data.error.code != undefined
				 */
				else{
					$('#pgBar').progressbar('value', 100);
					$('#xboxx').hide();
					userAdd(tag);
				}
			}
		});
	}
	function checkEmail(){
		var email = $('#email').val();
		if(email.indexOf('@', 1) != -1){
			$('#ex').hide();
			return true;
		}
		else{
			$('#ex').show();
			enableReg();
			return false;
		}
	}
	function checkPass(){
		if($('#pass').val().length < 6){
			$('#npx').show();
			enableReg();
			return false;
		}
		else{
			$('#npx').hide();
		}
		if($('#pass').val() != $('#cpass').val() ){
			$('#cx').show();
			enableReg();
			return false;
		}
		else{
			$('#cx').hide();
			return true;
		}
		}
		$('#pass').change(function(){
    	checkPass();
    });
    $('#cpass').change(function(){
    	checkPass();
    });
    $('#email').change(function(){
    	checkEmail()
    });
    //Added Register here 
    $('#nuser').change(function(){
    	var obj = {};
    	 obj.username = $(this).val();
    	checkUsername(obj);
    });
    //pass cpass
    $('#RegD').dialog({
    	autoOpen: false,
    	width: 460,
    	modal: true,
    	title: 'Register',
    	resizable: false,
		buttons: {
            "Register": function() {
            	var Obj = {};
            	Obj.username = $('#nuser').val();
            	Obj.password = $('#cpass').val();
            	Obj.email = $('#email').val();
            	Obj.psn = $('#Puser').val();
            	Obj.xbox = $('#Xuser').val();
            	Regvalidate(Obj);
            },
            "Cancel": function() {
             $(this).dialog("close");
            }
       },
       open:function(){
       	prgBar();
       },
       close:function(){
       	resetOnClose();
       }
    });
    $('.reg').click(function() {
        $('#RegD').dialog('open');
        return false;
    });
    //REG VALIDATION
     $('#AboutD').dialog({
    	autoOpen: false,
    	width: 500,
    	modal: true,
    	resizable: false
    });
    $('.about').click(function() {
        $('#AboutD').dialog('open');
        return false;
    });
        $('.about').click(function() {
        $('#AboutD').dialog('open');
        return false;
    });
    //Ended Register here
     $('.ops').click(function() { //ops is settings page
     	$('.filter').hide();
        $('#isoParent').fadeOut(100, function(){
        	$('#Settings').fadeIn(100);
        });
    });
        $('.house').click(function() { //home to get back to menu 
        	$('.filter').show();
        	$('#Settings').fadeOut(100,function(){
            $('#isoParent').fadeIn(100);
        	});
    });
    $('#loginD').dialog({
        autoOpen: false,
        width: 360,
        modal: true,
        resizable: false,
        title: 'Login',
        buttons: {
            "Login": function() {
            	var obj = {};
            	obj.username = $('[name=username]').val();
            	obj.password = $('[name=password]').val();
					validate(obj);       	
            },
            "Cancel": function() {
                $(this).dialog("close");
            }
        }       
    });
    $('#login').click(function() {
        $('#loginD').dialog('open');
        return false;
    });
    $('#rand').click(function(){
    	$container.isotope({sortBy: 'random'});
    	 return false;
    });
     $('#psn').click(function(){
 	$container.isotope({filter:'.psn'});
 });
 $('#xbox').click(function(){
 	$container.isotope({filter:'.xbox'});
 });
 function logoutAjax(){
 	$.ajax({
 		type:'GET',
 		url: 'login/logout',
 	});
 }
 $('.logout').click(function(){
 					logoutAjax();
					$container.isotope({filter:'.main'},ranLay());
					$('#login').show();
					$('.ops').hide();
					$(this).hide();
					$.each($('.filter'),function(){
					$(this).hide();
				});
	});
});
</script>
		<link rel="stylesheet" type="text/css" href="public/css/toolbar.css"/>
		<style>
		html,body {
  height: 98;
  overflow-x:hidden;
}
@font-face{
	font-family: "Technovia";
	src: url('public/fonts/technott.ttf');
}
			body {
				background-image: url('public/imgs/black.jpg');
				 background-size: auto;
				width: 99%;
				height: 100%;
			}
			#isoParent {
				margin:0 auto;
				border: 2px solid #4E4E4E;
			}
			.ui-dialog .ui-dialog-titlebar {
				padding: 0em;
			}
			.ui-dialog .ui-dialog-buttonpane {
				margin: 0px;
				padding: 0em;
			}
			.ui-button-text-only .ui-button-text {
				padding: 0em 0.4em;
			}
			.content-con{
				border: 1px solid white;
				border-radius:5px;
				float:left;
				padding: 5px;
				margin: 10px;
			}
			.ximg{
				width:12px;
				height:12px;
			}
			.ui-dialog .ui-dialog-content{
				padding:.5em;
			}
			.contentBox{
				border:2px solid white;
				border-radius:10px;
				box-shadow: 10px 10px 5px #333;
				background-color:#555555;
				margin: 10px;
			}
			.login{
				color:white;
				font-size:15px;
				float:right;
			}
			.login p{
				font-size:15px;
				text-align:center;
				font-family:Verdana, Tahoma, Geneva, sans-serif;
				text-decoration:none;
				position: relative;
				top: -6px;
			}
			.reg{
				width:200px;
				height:100px;
				background-color:#555555;
				color:white;
			}
			.reg p{
				font-size:31px;
				text-align:center;
				font-family:Verdana, Tahoma, Geneva, sans-serif;
				text-decoration:none;
			}
			.about{
				width:200px;
				height:100px;
				background-color:white;
				color:black;
				border:2px solid black;
			}
			.about p{ 
				font-size:31px;
				text-align:center;
				font-family:Verdana, Tahoma, Geneva, sans-serif;
				text-decoration:none;
				
			}
		/* selectors for filtering*/
			.main .home .filter .psn .logout .ops .house
			#grandad{
				width:100%;
				height:100%;
				position:absolute;
			}
			#Pplayercard{
				width:350px;
				height:150px;
			}
			#xCard{
				width:350px;
				height:150px;
			}
			#xavatar{
				height:100px;
				width:100px;
				float:left;
				
				}
			#Pavatar{
			width: 100px;
height: 100px;
border: 2px solid black;
margin: 22px 12px 22px 22px;
border-radius: 10px;
float: left;
padding: 3px;
			}
			#trophies{
				color:white;
				font-size:15px;
			width: 178px;
border: solid;
height: 105px;
float: left;
border-radius: 20px;
padding: 5px;
margin: 16px 0 10px 0;
			}
			.trophy{
				width: 20px;
				height: 20px;
				margin: 1px;
				margin-left: 4px;
			}
		</style>
	</head>
	<body>
		<div id="grandad">
		<div class="toolbar">
		<div id="banner">
			<span id="logo" >GameStalker</span>
			<div id="login" class="login toolEle"><p>Login</p></div>
			<div class="login toolEle logout" style="display:none"><p>LogOut</p></div>
			<!--  Changed here -->
			<div class="login toolEle ops" style="width:40px;display:none;left:1px"><div id="ops"></div></div>
			<!-- End Changed -->
			<div class="filter toolEle" style="display:none;float:left"><div id="xbox"></div></div>
			<div class="filter toolEle" style="display:none;float:left;left:-1px"><div id="psn"></div></div>
			<div class="filter toolEle" style="display:none;float:left;left:-2px"><div id="steam"></div></div>
			<div class="toolEle house" style="display:none;float:left"><div id="home"></div></div>
		</div>
		</div>
			<div id="isoParent" style="padding:10px">
			<div  class="contentBox reg item main"><p>Register</p></div>
			<div  class="contentBox about item main home"><p>About</p></div>
			<div id='Pplayercard' class='psn item contentBox'>
			<div id='Pavatar'></div>
			<div id='trophies'>
				<div><img id="palt" class="trophy" src="public/imgs/trophies/platinum.png"/></div>
				<div><img id="gold" class="trophy" src="public/imgs/trophies/gold.png"/></div>
				<div><img id="silver" class="trophy" src="public/imgs/trophies/silver.png"/></div>
				<div><img id="bronze" class="trophy" src="public/imgs/trophies/bronze.png"/></div>
			</div>
		</div>
		<div id="xCard" class='item contentBox xbox'>
				<div id="xavatar"></div>
				<div id ="score" style="float:left"></div>
			</div>
			</div>
		<div id="loginD" >
			<table>
				<tr>
					<td>Username: </td>
					<td>
					<input type="text" name="username" />
					</td>
					<td class="ximg" id="ux" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
				<tr>
					<td>Password: </td>
					<td>
					<input type="password" name="password"/>
					</td>
					<td class="ximg" id="px" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
			</table>
		</div>
		<div id="RegD" >
			<table>
				<tr>
					<td>Username: </td>
					<td>
					<input id="nuser" type="text" name="nuser" />
					</td>
					<td class="ximg" id="nux" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
				<tr>
					<td>Password: </td>
					<td>
					<input id="pass" type="password" name="pass"/>
					</td>
					<td class="ximg" id="npx" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
				<tr>
					<td>Confirm Password: </td>
					<td>
					<input type="password" id="cpass" name="cpass"/>
					</td>
					<td class="ximg" id="cx" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
				<tr>
					<td>Email: </td>
					<td>
					<input id="email" type="text" name="Email"/>
					</td>
					<td class="ximg" id="ex" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
				<tr>
					<td>PSN Username:  </td>
					<td>
					<input id="Puser"type="text" name="Puser"/>
					</td>
					<td class="ximg" id="psnx" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
				<tr>
					<td>Xbox Live Gamertag: </td>
					<td>
					<input id="Xuser" type="text" name="Xuser"/>
					</td>
					<td class="ximg" id="xboxx" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
				<!--<tr>
					<td>Steam ID: </td>
					<td>
					<input type="text" name="Suser"/>
					</td>
					<td class="ximg" id="px" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>-->
			</table>
		</div>
		<div id="AboutD" >
				<p>GameStalker is an aggregated service that combines the statistics from Xbox Live, Playstation Network and Steam 
				to allow the Gamer to compare their performance with friends across all platforms. </p>
				<p>GameStalker will be updated with RSS Feeds and Platform News soon!</p>
		</div>
		<div id="Settings" style="display:none;color:white"> 

			<div style="margin:0 auto; position:relative"> <!-- Attempted to learn div didnt work as intended-->
				<div style="position:relative;left:-1px;">Username ID's</div><div style="position:relative;left:1px"><input type="button" value="Edit"/></div>
			  </div>
			   <table style="margin:0 auto; position:relative">
				<tr><td>RSS Feeds</td></tr>
				<tr>
					<td></td><td>PSN</td><td>Xbox</td><td>PC</td>
				</tr>
				<tr>
					<td>Gamespot</td>
					<td><input type="checkbox" name="GamespotPSN" value="GSpsn" /></td>
					<td><input type="checkbox" name="GamespotXbox" value="GSxbox" /></td>
					<td><input type="checkbox" name="GamespotSteam" value="GSsteam" /></td>
				</tr>
				<tr>
					<td>IGN</td>
					<td><input type="checkbox" name="IGNPSN" value="ignpsnvalue" /></td>
					<td><input type="checkbox" name="IGNXbox" value="ignxboxvalue" /></td>
					<td><input type="checkbox" name="IGNSteam" value="ignsteamvalue" /></td>
				</tr>
			</table>
		</div>
		</div>
	</body>
</html>
