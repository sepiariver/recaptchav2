var recaptchav2Callback = function() {
	var captchas = document.querySelectorAll('.g-recaptcha');
	captchas.forEach(function(captcha){
		grecaptcha.render(captcha.id, {'sitekey':captcha.dataset.sitekey});
	});
}
