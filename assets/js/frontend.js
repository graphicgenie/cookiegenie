/**
 * CookGenie frontend js.
 *
 *  @package CookGenie Plugin Template/JS
 */
function createCookie(name, value, days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		var expires = "; expires=" + date.toGMTString();
	} else var expires = "";
	document.cookie = name + "=" + value + expires + "; path=/; domain=." + location.hostname;
}

function AllowCookies() {
	createCookie('cookiegenie_consent', data.version + '.' + Date.now(), data.expire);
	window.yett.unblock();

	const element = document.getElementById("cg-container");
	element.remove();
}

function DisallowCookies() {
	createCookie('cookiegenie_block', data.version + '.' + Date.now(), data.expire);

	const element = document.getElementById("cg-container");
	element.remove();
}