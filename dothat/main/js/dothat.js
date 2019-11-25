function _(e) {
	return document.getElementById(e);
}

function ajaxLoad(method, url_request, request_attr, response) {
	let xhttp = new XMLHttpRequest();
	xhttp.open(method, url_request+request_attr, true);
	xhttp.send();
	xhttp.onreadystatuschange = function () {
		if (this.readyState == 4 && this.status == 200) {
			response = this.responseText;
		}
	}
	return response;
}

window.onscroll = function() {richTop()};

function richTop() {
	var d = _("head_top");
	if (window.scrollY == 0) {
		d.style.boxShadow = "inherit";
	}else {
		d.style.boxShadow = "0px 2px 2px -2px #616161";
	}		
}


function openMenu(){
	if (_('menu').style.transform !== 'translateX(0%)') {
		_('menu_back').style.display = 'block';
		_('menu').style.transform = 'translateX(0%)';
		_('menu_button').innerHTML = "⇶";
	}else {
		_('menu').style.transition = 'all 0.3s ease';
		_('menu').style.transform = 'translateX(100%)';
		_('menu_button').innerHTML = "⇶";
		_('menu_back').style.display = 'none';
	}
}

function dropdown() {
	var menu =  _('drop_menu');
	var cssProp = window.getComputedStyle(menu, null).getPropertyValue("display");
	if (cssProp == "block") {
		menu.style.display="none";  	
	}else {
		menu.style.display="block";
	}

}

function dropdownon() {
	_('drop_menu').style.display= "block";
}

function undropdown() {
  _('drop_menu').style.display= "none";
}

function validation () {
	_('uname').value = 0 // Test.check()
	_('password').value = 0 // Test.check()
	
}
function check_sign_up () {
	_('username').value = 0 // Test.check()
	_('password').value = 0 // Test.check()
	_('email').value = 0 // Test.check()
	_('confrm_password').value = 0 // Test.check()
	_('first_name').value = 0 // Test.check()
	_('last_name').value = 0 // Test.check()
	_('birthday').value = 0 // Test.check()
	_('birthday').value = 0 // Test.check()
}