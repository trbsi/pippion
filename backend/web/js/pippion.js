/*Postavi trenutno lokalno korisnikovo vrijeme skrivenom polju da se može spremiti u bazu
HOW TO USE: 1. set class of input field you want to set time to .js-setTime
2. add onsubmit="setCurrentTime()" to form you are submitting
*/
function setCurrentTime()
{
	//YYYY-MM-DD HH:MM:SS
	var d = new Date();
	var time=d.getFullYear()+'-'+if0((d.getMonth()+1))+'-'+if0(d.getDate())+' '+if0(d.getHours())+':'+if0(d.getMinutes())+':'+if0(d.getSeconds());
	$(".js-setTime").val(time);
}

/*
This function returns current time so I can just call it when I need to set current time like this:
var time = returnCurrentTime();
*/
function returnCurrentTime()
{
	//YYYY-MM-DD HH:MM:SS
	var d = new Date();
	var time=d.getFullYear()+'-'+if0((d.getMonth()+1))+'-'+if0(d.getDate())+' '+if0(d.getHours())+':'+if0(d.getMinutes())+':'+if0(d.getSeconds());
	return time;
}

//vezano za funkciju gore, ako mjesec, dan... je manji od 10 stavi nulu ispred brojke inače će biti "1" umjesto "01"
function if0(x)
{
	if(x<10)
	{
		x='0'+x;
	}
	return x;	
}

//create live clock for auction based on UTC time
//http://www.smarttutorials.net/live-server-time-clock-using-php-and-javascript/
/*HOW TO USE:
<div class="showUTCTime"><script>startTime()</script></div>
*/
function startTime()
{	
	var today=new Date();
	var h=today.getUTCHours();
	var m=today.getUTCMinutes();
	var s=today.getUTCSeconds();
	var day=today.getUTCDate();
	var month=today.getUTCMonth()+1;
	var year=today.getUTCFullYear()
	
	// add a zero in front of numbers<10
	m=if0(m);
	s=if0(s);
	h=if0(h);
	day=if0(day);
	month=if0(month);
	$('.showUTCTime').text(day+"."+month+"."+year+" "+h+":"+m+":"+s);
	setTimeout("startTime()",1000);
}
	
//jquery.datetimepicker.js	
function initalizeDateTimePicker()
{
	//datetime picker for input
	$('.js-datetimepicker').datetimepicker
	({
	  format:'Y-m-d H:i:s',
	  validateOnBlur:true,
	  defaultDate:new Date(),
	  //mask:true,
	});

	//date picker for input
	$('.js-datepicker').datetimepicker
	({
	  format:'Y-m-d',
	  timepicker:false,
	  validateOnBlur:true,
	  defaultDate:new Date(),
	  //mask:true,
	});
	

}

//validate if correct file type was chonse for input file
function fileValidation(allowedFiles, element)
{
		//https://developer.mozilla.org/en-US/docs/Web/API/FileList
		//http://stackoverflow.com/questions/10703102/jquery-get-all-filenames-inside-input-file?lq=1
		var files=$(element).prop("files");
		// loop trough files
		for (var i = 0; i < files.length; i++) 
		{
		
			// get item
			file = files[i];
		
			var value=file.name; //value of file field "this.is_file.jpg"
			var lastIndex=value.lastIndexOf('.')+1; //get last index of "." from value = 12
			var ext=value.substr(lastIndex).toLowerCase(); //extension = get string from that lastIndex+1 = 13-15 (jpg)
		
			if(jQuery.inArray(ext, allowedFiles)==-1) 
			{
				$(element).val(null); //clear default(real) input field
				$(element).filestyle('clear');// if you are using bootstrap-filestyle.js, clear bootstrap input text
				alert("You have selected invalid file type. Choose another file type.");
			}
			//if user wants to upload more than 10 images
			if(i>9)
			{
				$(element).val(null); //clear default(real) input field
				$(element).filestyle('clear');// if you are using bootstrap-filestyle.js, clear bootstrap input text
				alert("You can upload maximum 10 images");
			}
		}
}

$(document).ready(function(e) 
{
	
	//set cookie if user toggles menu so it can stay condensed or wider,  depending user's wish
	//by default menu is wide on every page load, so you know it cookie doesn't exist, so if user click on button to condens menu you set cookie to true
	//everytime user click on tahat link you check value of cookie and reverse it
	if($(window).width() > 768)
	{
		var layout_condensed_toggle=$(".js-condensMenuCookieSetBtn");
		var cookie_menu=$.cookie('condens_menu');
		
		if(cookie_menu=="true")
		{
			$(".js-condensMenuM").click();
		}
		
		layout_condensed_toggle.click(function()
		{
			if(!cookie_menu) 
			{
				$.cookie('condens_menu', 'true', { expires: 365, path: '/' });
			}
			else if(cookie_menu=="true")
			{
				$.cookie('condens_menu', 'false', { expires: 365, path: '/' });
			}
			else if(cookie_menu=="false")
			{
				$.cookie('condens_menu', 'true', { expires: 365, path: '/' });
			}
		});
	}
	
	
	//nice boostrap look for file upload field
	$(":file").filestyle({buttonName: "btn-primary"});
	
	//BOOSTRAP TOOLTIP FUNKCIJA, WEbARCH THEME TOOLTIP
	$('.tooltipp').tooltip();
	
	//colorbox images lightbox
	$(".group1_colorbox").colorbox({width:"100%", height:"100%", rel:"group1_colorbox"});
	//colorbox profile pic lightbox
	$(".profile_pic_colorbox").colorbox({width:"80%", height:"90%"});

	initalizeDateTimePicker();
	
	//input file validator, validate if correct file type was chosen
	$('.js-file-validation-image').change(function()
	{
			var allowedFiles=["jpg","jpeg","png","gif"];
			fileValidation(allowedFiles, this);
	});

	$('.js-file-validation-pedigree').change(function()
	{
			var allowedFiles=["jpg","jpeg","png","gif", "pdf"];
			fileValidation(allowedFiles, this);
	});
	
	//slim scrollbar /assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js
	$('.scrollbar-inner').scrollbar();


	
});
