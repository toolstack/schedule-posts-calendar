function GetScriptIndex(name)
{
	// Loop through all the scripts in the current document to find the one we want.
	for( i = 0; i < document.scripts.length; i++) 
		{
		// Make a temporary copy of the URI and find out where the query string starts.
		var tmp_src = String(document.scripts[i].src);
		var qs_index = tmp_src.indexOf('?');

		// Check if the script is the script we are looking for and if it has a QS, if so return the current index.
		if( tmp_src.indexOf(name) >= 0 && qs_index >= 0)
			{
			return i;
			}
		
		}
		
	return -1;
}

function GetScriptVariable(index, name, vardef)
{
	// If a negitive index has been passed in it's because we didn't find any matching script with a query
	// string, so just return the default value.
	if( index < 0 )
		{
		return vardef;
		}

	// Make a temporary copy of the URI and find out where the query string starts.
	var tmp_src = String(document.scripts[index].src);
	var qs_index = tmp_src.indexOf('?');

	// Split the query string ino var/value pairs.  ie: 'var1=value1', 'var2=value2', ...
	var params_raw = tmp_src.substr(qs_index + 1).split('&');

	// Now look for the one we want.
	for( j = 0; j < params_raw.length; j++)
		{
		// Split names from the values.
		var pp_raw = params_raw[j].split('=');

		// If this is the one we're looking for, simply return it.
		if( pp_raw[0] == name )
			{
			// Check to make sure a value was actualy passed in, otherwise we should return the default later on.
			if( typeof(pp_raw[1]) != 'undefined' )
				{
				return pp_raw[1];
				}
			}
		}

	// If we fell through the loop and didn't find ANY matching variable, simply return the default value.
	return vardef;
}

function AddCalendar()
{
	// Find the timesteampdiv <div> in the current page.
	var parent = document.getElementById('timestampdiv');
	
	// If we didn't find the parent, don't bother doing anything else.
	if( parent )
		{
		// Create a new div element and setup it's style and id to be inserted.
		var elmnt = document.createElement("div");
		elmnt.setAttribute('id', 'calendarHere');
		elmnt.setAttribute('style', 'position:relative;height:230px;');

		// Insert the div we just created in to the current page as the first child under 'timestampdiv'. 
		parent.insertBefore(elmnt,parent.firstChild);

		// Setup a date object to use to set the inital calendar date to display from the values in the WordPress controls.
		var startingDate = new Date();
		startingDate.setDate(document.getElementById('jj').value);
		startingDate.setMonth(document.getElementById('mm').selectedIndex);
		startingDate.setFullYear(document.getElementById('aa').value);
		startingDate.setHours(document.getElementById('hh').value);
		startingDate.setMinutes(document.getElementById('mn').value);
		
		// Retrive the script options from the URI
		var GSI = GetScriptIndex('schedule-posts-calendar.js');
		var startOfWeek = GetScriptVariable(GSI, 'startofweek', 7);
		var theme = GetScriptVariable(GSI, 'theme', 'omega');

		// Finally create the calendar and replace the <div> we inserted earlier with the proper calendar control.  Also, set the calendar display properties and then finnally show the control.
		myCalendar = new dhtmlXCalendarObject("calendarHere");
		myCalendar.setWeekStartDay(startOfWeek);
		myCalendar.setDate(startingDate);
		myCalendar.setSkin(theme);
		myCalendar.show();
		
		// We have to attach two events to the calendar to catch when the user clicks on a new date or time.  They both do the exactly same thing, but the first catches the date change and the second the time change.
		var myEvent = myCalendar.attachEvent("onClick", function (selectedDate){
				document.getElementById('mm').selectedIndex = selectedDate.getMonth();
				document.getElementById('jj').value = selectedDate.getDate();
				document.getElementById('aa').value = selectedDate.getFullYear();
				document.getElementById('hh').value = selectedDate.getHours();
				document.getElementById('mn').value = selectedDate.getMinutes();})
		var myEvent = myCalendar.attachEvent("onChange", function (selectedDate){
				document.getElementById('mm').selectedIndex = selectedDate.getMonth();
				document.getElementById('jj').value = selectedDate.getDate();
				document.getElementById('aa').value = selectedDate.getFullYear();
				document.getElementById('hh').value = selectedDate.getHours();
				document.getElementById('mn').value = selectedDate.getMinutes();})
	}
}

// Use an event listerner to add the calendar on a page load instead of .OnLoad as we might otherwise get overwritten by another plugin.
window.addEventListener ? window.addEventListener("load",AddCalendar,false) : window.attachEvent && window.attachEvent("onload",AddCalendar);