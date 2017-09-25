<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$title;?></title>
<meta name="keywords" content="yellow blog template, free html css layout" />
<meta name="description" content="yellow blog template, free html css layout from Ftemplate.ru" />
<link href="/templatemo_style.css" rel="stylesheet" type="text/css" />
<link href="/smile.css" rel="stylesheet" type="text/css" />
<link href="/auth.css" rel="stylesheet" type="text/css" />



<script language="javascript" type="text/javascript">
function clearText(field)
{
    if (field.defaultValue === field.value) field.value = '';
    else if (field.value === '') field.value = field.defaultValue;
}
</script>

<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".smile").click(function(){
				var smile = $(this).attr('alt');
				var text = $.trim($("#text").val());
				$("#text").focus().val(text + ' ' + smile + ' ');
			});
		});
	</script>

</head>
<body>
     
<div id="templatemo_site_title_bar_wrapper">
	<div id="templatemo_site_title_bar">
	    <div id="site_title">
            <h1><a href="http://www.Ftemplate.ru" target="_parent">Yellow Blog
                <span>free html css template</span>
            </a></h1>
        </div>
        
        
            
            <div id="search_box">
            <form action="/admin/search/" method="get">
                <input type="text" value="Enter keyword here..." name="query" size="10" id="searchfield" title="searchfield" onfocus="clearText(this)" onblur="clearText(this)" />
                <input type="submit" id ="searchbutton" value="" alt="Поиск"  title="Поиск" />
            </form>
        </div>
            
    
    </div>
    
</div>
    
   

<div id="templatemo_menu_wrapper">
	<div id="templatemo_menu">
	    <ul>
                <li><a href="/admin/about/" class="current fast">Обо мне</a></li>
            <li><a href="/admin/articles/">Блог</a></li>
        </ul>
    </div>
</div> 

<div id="templatemo_content_wrapper_outer">

	<div id="templatemo_content_wrapper_inner">
    
    	<div id="templatemo_content_wrapper">
        	
            <div id="templatemo_content">
            	<div class="content_bottom"></div>
            	
                <?=$content;?>
				
                <div id="side_column">
                    <div class="side_column_section">
                
                	<div class="ads_125_125 right_padding_10">
                    	<a href="#"><img src="/images/templatemo_ads.jpg" alt="image" /></a>
                    </div>
                    
                    <div class="ads_125_125">
                    	<a href="#"><img src="/images/templatemo_ads.jpg" alt="image" /></a>
                    </div>
                    
                </div>
                    
                
                <section class="container">
                <div class="login">
                  <h1>Войти в личный кабинет</h1>
                  <form method="post" action="index.html">
                    <p><input type="text" name="login" value="" placeholder="Логин или Email"></p>
                    <p><input type="password" name="password" value="" placeholder="Пароль"></p>
                    <p class="remember_me">
                      <label>
                        <input type="checkbox" name="remember_me" id="remember_me">
                        Запомнить меня
                      </label>
                    </p>
                    <p class="submit"><input type="submit" name="commit" value="Войти"></p>
                  </form>
                </div>

                <div class="login-help">
                  <a href="index.html">Забыли пароль?</a> Восстановите его!
                </div>
              </section>          
                
                
                <div class="cleaner_h30">&nbsp;</div>
                
                <?=$archive;?>
                
                <div class="cleaner_h30">&nbsp;</div>
                
                <?=$popular_articles;?>
                                
                </div> <!-- end of side column -->
            
            	<div class="cleaner"></div>
            </div>
        
        	<div class="cleaner"></div>
        </div>
        
        <div class="cleaner"></div>        
    </div>

</div>

<div id="templatemo_footer_wrapper">

	<div id="templatemo_footer">
    
        
        <div class="section_w200">
        
        	<h4>Services</h4>
        	<ul class="footer_menu_list">
            	<li><a href="#">Lorem ipsum dolor</a></li>
                <li><a href="#">Cum sociis</a></li>
                <li><a href="#">Donec quam</a></li>
                <li><a href="#">Nulla consequat</a></li>
                <li><a href="#">In enim justo</a></li>               
            </ul>
            
        </div>
        
        <div class="section_w200">
        
	        <h4>About</h4>
        	<ul class="footer_menu_list">
                <li><a href="#">Nullam quis</a></li>
                <li><a href="#">Sed consequat</a></li>
                <li><a href="#">Cras dapibus</a></li> 
            	<li><a href="#">Lorem ipsum dolor</a></li>
                <li><a href="#">Cum sociis</a></li>              
            </ul>
            
        </div>
        
        <div class="section_w200">

			<h4>Partners</h4>       
        	<ul class="footer_menu_list">
            	<li><a href="http://www.Ftemplate.ru" target="_parent">Website Templates</a></li>
                <li><a href="http://www.flashmo.com" target="_parent">Flash Templates</a></li>
                <li><a href="http://www.layermo.com" target="_parent">Wordpress Themes</a></li>
              	<li><a href="http://www.webdesignmo.com" target="_parent">Web Design Tips</a></li>
                <li><a href="http://www.koflash.com" target="_blank">Flash Gallery</a></li>               
          </ul>
            
        </div>
        
        <div class="section_w260">
        
        	<h4>Privacy Policy</h4>
        	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut non rutrum arcu. Vestibulum ornare dolor eget leo placerat sed tincidunt dolor interdum</p>
            
			<div class="cleaner_h10"></div>
            
            <a href="http://validator.w3.org/check?uri=referer"><img style="border:0;width:88px;height:31px" src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" width="88" height="31" vspace="8" border="0" /></a>
    			<a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px"  src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!" vspace="8" border="0" /></a>
            
        </div>
        
        <div class="cleaner_h20"></div>
        
        <div class="section_w860">
        	Copyright © 2024 <a href="#">Your Company Name</a> | Designed by <a href="http://www.Ftemplate.ru" target="_parent">Free CSS Templates</a>
        </div>
            
    </div> <!-- end of footer -->
</div>
</body>
</html>