function validate_me(param){
	if(param=='1'){
		if(!document.getElementById('chk_read').checked){
			alert('Accept our terms and condition please');
			return false;
		} else {
			return true;
		}
	}
	if(param=='2'){
		if(jQuery("#txtEmailAddress").val()==''){
			alert('Email address required');
			jQuery("#txtEmailAddress").focus();
			return false;
		} else if(!checkEmail("txtEmailAddress")){
			alert('Valid email address required');
			jQuery("#txtEmailAddress").focus();
			return false;
		} else if(jQuery("#txtDomainName").val()==''){
			alert('Domain name required');
			jQuery("#txtDomainName").focus();
			return false;
		} else if(jQuery("#txtPurchaseCode").val()==''){
			alert('Purchase code required');
			jQuery("#txtPurchaseCode").focus();
			return false;
		} else {
			return true;
		}
	}
	if(param=='3'){
		if(jQuery("#txtDocRoot").val()==''){
			alert('Document root path required');
			jQuery("#txtDocRoot").focus();
			return false;
		} else if(jQuery("#txtHostName").val()==''){
			alert('Database host name required');
			jQuery("#txtHostName").focus();
			return false;
		} else if(jQuery("#txtDBUserName").val()==''){
			alert('Database user required');
			jQuery("#txtDBUserName").focus();
			return false;
		} else if(jQuery("#txtDBName").val()==''){
			alert('Database name required');
			jQuery("#txtDBName").focus();
			return false;
		} else {
			return true;
		}
	}
}

function checkEmail(txtEmail) {
    var email = document.getElementById(txtEmail);
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!filter.test(email.value)) {
    	return false;
 	}
	return true;
}