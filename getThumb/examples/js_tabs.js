var jsTabs = new function()
{

	this.contents = [];
	
	this.setContents = function(contents)
	{
		this.contents = contents;
	}

    this.setActive = function(name, active)
	{
		if (active)
		{
			document.getElementById('content_'+name).style.display = 'block';
			document.getElementById('nav_'+name).className = 'active';
		}
		else
		{
			document.getElementById('content_'+name).style.display = 'none';
			document.getElementById('nav_'+name).className = '';
		}
	}

    this.show = function(name)
	{
        for (i in this.contents)
        	this.setActive(this.contents[i], false);
        	
        this.setActive(name, true);
	}
	
	this.create = function(id)
	{
		var first = true;
		var e;
		var html = '<ul class="nav">';
		var name;

        for (i in this.contents)
        {
			name = this.contents[i];
        	
        	html += '<li';
        	
			if (first)
				html += ' class="first"';
			
			html += '><a';
			
			if (first)
				html += ' class="active"';

			html += ' id="nav_'+name+'"';			
			html += ' href="javascript:jsTabs.show(\''+name+'\')">';
			
			e = document.getElementById('title_'+name);
			
			html += e.innerHTML;
			html += '</a></li>';
			
			if (!first)
				document.getElementById('content_'+name).style.display = 'none';
        	
        	first = false;
        }
		
		html += '</ul>';
		
		document.getElementById(id).innerHTML = html;
	}


}