function darkmode_init()
{
	let darkmodeSwitch = document.querySelector('header .darkmode');
	
	let darkmodeCookie = {
		set:function(key,value,time,path,secure=false)
		{
			let expires = new Date();
			expires.setTime(expires.getTime() + time);
			var path   = (typeof path !== 'undefined') ? pathValue = 'path=' + path + ';' : '';
			var secure = (secure) ? ';secure' : '';
			
			document.cookie = key + '=' + value + ';' + path + 'expires=' + expires.toUTCString() + secure;
		},
		get:function()
		{
			let keyValue = document.cookie.match('(^|;) ?darkmode=([^;]*)(;|$)');
			return keyValue ? keyValue[2] : null;
		},
		remove:function()
		{
			document.cookie = 'darkmode=; Max-Age=0; path=/';
		}
	};
	
	
	if(darkmodeCookie.get() == 'true')
	{
		darkmodeSwitch.classList.add('active');
		document.body.classList.add('darkmode');
	}
	
	
	darkmodeSwitch.addEventListener('click', (event) => {
		event.preventDefault();
		event.target.classList.toggle('active');
		document.body.classList.toggle('darkmode');
		
		if(document.body.classList.contains('darkmode'))
		{
			darkmodeCookie.set('darkmode','true',2628000000,'/',false);
		}
		else
		{
			darkmodeCookie.remove();
		}
	});
}