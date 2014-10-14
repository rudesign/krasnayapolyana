<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="generator" content="PSPad editor, www.pspad.com">
<title>getthumb examples</title>

<link rel="stylesheet" type="text/css" media="screen" href="main.css" />
<script type="text/javascript" src="js_tabs.js"></script>

</head>
<body>

<div class="container">

<div id="tabs"></div>
	
<br class="clear" />



<div class="content" id="content_default">
<h2 id="title_default">Default</h2>
	
<div id="code_default"><img src="../getthumb.php?src=examples/pic1.jpg" alt="" />
<img src="../getthumb.php?src=examples/pic2.jpg" alt="" /></div>

	<h4>Parameters</h4>
	<dl>
		<dt class="first">src</dt>
		<dd class="first">filename path relative to getthumb</dd>  
	</dl>	
	<br class="clear" />
	
</div>



<div class="content" id="content_stretch">
<h2 id="title_stretch">Stretch</h2>
	
<div id="code_stretch"><img src="../getthumb.php?src=examples/pic1.jpg&amp;keep_ratio=0&amp;height=200" alt="" />
<img src="../getthumb.php?src=examples/pic2.jpg&amp;keep_ratio=0&amp;width=200" alt="" /></div>

	<h4>Parameters</h4>
	<dl>
		<dt class="first">src</dt>
		<dd class="first">filename path relative to getthumb</dd>  
		<dt>keep_ratio</dt>
		<dd>keep the aspect ratio of the source image? (0 or 1)</dd>  
		<dt>width</dt>
		<dd>width of the destination image in pixels</dd>  
		<dt>height</dt>
		<dd>height of the destination image in pixels</dd>  
	</dl>
	<br class="clear" />
	
</div>



<div class="content" id="content_crop">
<h2 id="title_crop">Crop</h2>

<div id="code_crop"><img src="../getthumb.php?src=examples/pic1.jpg&amp;crop=1" alt="" />
<img src="../getthumb.php?src=examples/pic2.jpg&amp;crop=1" alt="" /></div>

	<h4>Parameters</h4>
	<dl>
		<dt class="first">src</dt>
		<dd class="first">filename path relative to getthumb</dd>  
		<dt>crop</dt>
		<dd>crop source image? (0 or 1)</dd>  
		<dt>width</dt>
		<dd>width of the destination image in pixels</dd>  
		<dt>height</dt>
		<dd>height of the destination image in pixels</dd>  
	</dl>
	<br class="clear" />
	
</div>



<div class="content" id="content_crop_overlay">
<h2 id="title_crop_overlay">Crop &amp; Overlay</h2>
	
<div id="code_crop_overlay"><img src="../getthumb.php?src=examples/pic1.jpg&amp;crop=1&amp;overlay=examples/overlay.png&amp;quality=100" alt="" />
<img src="../getthumb.php?src=examples/pic2.jpg&amp;crop=1&amp;overlay=examples/overlay.png&amp;quality=100" alt="" /></div>

	<h4>Parameters</h4>
	<dl>
		<dt class="first">src</dt>
		<dd class="first">filename path relative to getthumb</dd>  
		<dt>crop</dt>
		<dd>crop source image? (0 or 1)</dd>  
		<dt>width</dt>
		<dd>width of the destination image in pixels</dd>  
		<dt>height</dt>
		<dd>height of the destination image in pixels</dd>  
		<dt>overlay</dt>
		<dd>overlay image path relative to getthumb</dd>  
	</dl>
	<br class="clear" />
	
</div>



<div class="content" id="content_brightness">
<h2 id="title_brightness">Brightness</h2>
	
<div id="code_brightness"><img src="../getthumb.php?src=examples/pic2.jpg&amp;brightness=0.25" alt="" />
<img src="../getthumb.php?src=examples/pic2.jpg&amp;brightness=0.5" alt="" />
<img src="../getthumb.php?src=examples/pic2.jpg&amp;brightness=0.75" alt="" /></div>

	<h4>Parameters</h4>
	<dl>
		<dt class="first">src</dt>
		<dd class="first">filename path relative to getthumb</dd>  
		<dt class="first">brightness</dt>
		<dd class="first">brightness value (-1 to 1)</dd>  
	</dl>	
	<br class="clear" />
	
</div>



<div class="content" id="content_contrast">
<h2 id="title_contrast">Contrast</h2>

<div id="code_contrast"><img src="../getthumb.php?src=examples/pic1.jpg&amp;contrast=0.25" alt="" />
<img src="../getthumb.php?src=examples/pic1.jpg&amp;contrast=0.5" alt="" />
<img src="../getthumb.php?src=examples/pic1.jpg&amp;contrast=0.75" alt="" /></div>

	<h4>Parameters</h4>
	<dl>
		<dt class="first">src</dt>
		<dd class="first">filename path relative to getthumb</dd>  
		<dt class="first">contrast</dt>
		<dd class="first">contrast value (-1 to 1)</dd>  
	</dl>	
	<br class="clear" />

</div>


<div class="content" id="content_watermark">
<h2 id="title_watermark">Watermark</h2>

<div id="code_watermark"><img src="../getthumb.php?src=examples/pic1.jpg&amp;overlay=examples/watermark.png" alt="" />
<img src="../getthumb.php?src=examples/pic1.jpg&amp;overlay=examples/watermark.png&overlay_align=right&amp;overlay_valign=bottom" alt="" />
<img src="../getthumb.php?src=examples/pic1.jpg&amp;overlay=examples/watermark.png&overlay_x=20&amp;overlay_y=20" alt="" /></div>

	<h4>Parameters</h4>
	<dl>
		<dt class="first">src</dt>
		<dd class="first">filename path relative to getthumb</dd>  
		<dt class="first">overlay</dt>
		<dd class="first">overlay image path relative to getthumb</dd>  
		<dt class="first">overlay_x</dt>
		<dd class="first">the x position of the overlay image</dd>  
		<dt class="first">overlay_y</dt>
		<dd class="first">the y position of the overlay image</dd>  
		<dt class="first">overlay_align</dt>
		<dd class="first">the horizontal align of the overlay, can be: left, center, right</dd>  
		<dt class="first">overlay_valign</dt>
		<dd class="first">the vertical align of the overlay, can be: top, center, bottom</dd>  
	</dl>	
	<br class="clear" />

</div>



<br class="clear" />


<div class="footer">

<a id="default_values_link" href="javascript:showDefaultValues()">Show default values</a>

<div id="default_values" style="display: none">

<h4>Default parameter values</h4>		
<table class="table">
	<tr><td>width</td><td>100</td></tr>
	<tr><td>height</td><td>100</td></tr>
	<tr><td>quality</td><td>70</td></tr>
	<tr><td>keep_ratio</td><td>1</td></tr>
	<tr><td>crop</td><td>0</td></tr>
	<tr><td>overlay</td><td>false</td></tr>
	<tr><td>brightness</td><td>0</td></tr>
	<tr><td>contrast</td><td>0</td></tr>
	<tr><td>src_x</td><td>false</td></tr>
	<tr><td>src_y</td><td>false</td></tr>
	<tr><td>src_w</td><td>false</td></tr>
	<tr><td>src_h</td><td>false</td></tr>
</table>	

</div>

</div>

</div>

<script type="text/javascript">

// create tabs
jsTabs.setContents(['default', 'stretch', 'crop', 'crop_overlay', 'brightness', 'contrast', 'watermark']);
jsTabs.create('tabs');

// create code's blocks
for (i in jsTabs.contents)
{
	var name = jsTabs.contents[i];
	var content = document.getElementById('content_'+name);	
	var code = document.getElementById('code_'+name).innerHTML;

	code = code.replace(new RegExp("\<", "g"), '&lt;');
	code = code.replace(new RegExp("\>", "g"), '&gt;');

	code = code.replace(new RegExp("\\n", "g"), '<br />');
	
	content.innerHTML += '<h4>Code<h4><code>'+code+'</code>';	
}

// function for default values' block
function showDefaultValues()
{
	var e = document.getElementById('default_values');
	var d = e.style.display == 'none' ? 'block' : 'none';
	
	e.style.display = d;
	
	e = document.getElementById('default_values_link');
	
	if (d == 'none')
		e.innerHTML = 'Show default values';
	else
		e.innerHTML = 'Hide default values';
}
	 
</script>


</body>
</html>
