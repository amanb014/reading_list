var start_date;
var end_date;
var page_count;
var average_pages = document.getElementById("averagepages");

function enable_calculate_btn() {
	start_date = document.getElementById("start_date").value;
	end_date = document.getElementById("end_date").value;
	page_count = document.getElementById("pagecount").value;
	
	if((start_date < end_date) && page_count > 0) {
		calculate_average();

	}
	console.log("enable_calculate_btn");
}

function calculate_average() {
	var start = new Date(start_date);
	var end = new Date(end_date);

	var diff = millis_to_days(end-start);

	average_pages.value = round_two_decimals(page_count / diff);
}

function millis_to_days(milliseconds) {
	return (milliseconds / 86400000);
}

function round_two_decimals(int) {

	return Math.round(int, 2);
}