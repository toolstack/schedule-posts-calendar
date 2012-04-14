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
		// Retrive the script options from the URI
		var GSI = GetScriptIndex('schedule-posts-calendar.js');
		var startOfWeek = GetScriptVariable(GSI, 'startofweek', 7);
		var theme = GetScriptVariable(GSI, 'theme', 'omega');
		var popupCalendar = GetScriptVariable(GSI, 'popupcalendar', 0);

		// Create a new div element and setup it's style and id to be inserted.
		if( popupCalendar == 0 )
			{
			// If we're using the inline calendar, make a div.
			var elmnt = document.createElement("div");
			elmnt.setAttribute('id', 'calendarHere');
			elmnt.setAttribute('style', 'position:relative;height:230px;');
			}
		else
			{
			// If we're using a popup calendar, make an input field.
			var elmnt = document.createElement("input");
			elmnt.setAttribute('id', 'calendarHere');
			elmnt.setAttribute('type', 'text');
			}
		
		// Insert the div we just created in to the current page as the first child under 'timestampdiv'. 
		parent.insertBefore(elmnt,parent.firstChild);

		// Get the current date/time from the form.
		var sDay = new String(document.getElementById('jj').value);
		var sMon = new String(document.getElementById('mm').selectedIndex);
		var sYear = new String(document.getElementById('aa').value);
		var sHour = new String(document.getElementById('hh').value);
		var sMin = new String(document.getElementById('mn').value);
		
		// Setup a date object to use to set the inital calendar date to display from the values in the WordPress controls.
		var startingDate = new Date();
		startingDate.setDate(sDay);
		startingDate.setMonth(sMon);
		startingDate.setFullYear(sYear);
		startingDate.setHours(sHour);
		startingDate.setMinutes(sMin);

		// If we're replacing the stock WP fields, set the new field's starting date.  Make sure the formating looks right with 0 padded day/mon/hour/minute fields.
		if( popupCalendar == 1 )
			{
			// The index returned is 0 based but we need it to be 1 based to create the string.
			sMon = new String(document.getElementById('mm').selectedIndex + 1);
			
			var dateString = '';
			if( sDay.length < 2 ) { dateString += '0'; }
			dateString += sDay + '/';
			if( sMon.length < 2 ) { dateString += '0'; }
			dateString += sMon + '/' + sYear + ' ';
			if( sHour.length < 2 ) { dateString += '0'; }
			dateString += sHour + ':';
			if( sMin.length < 2 ) { dateString += '0'; }
			dateString += sMin;

			document.getElementById('calendarHere').value = dateString;
			}

		// Finally create the calendar and replace the <div>/<input> we inserted earlier with the proper calendar control.  Also, set the calendar display properties and then finnally show the control.
		myCalendar = new dhtmlXCalendarObject("calendarHere");
		myCalendar.setWeekStartDay(startOfWeek);
		myCalendar.setDate(startingDate);
		myCalendar.setSkin(theme);
		myCalendar.setDateFormat('%d/%m/%Y %H:%i');

		// Only show the calendar if its inline
		if( popupCalendar == 0 ) { myCalendar.show(); }
		
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