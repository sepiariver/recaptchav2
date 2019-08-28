<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=[[++cultureKey]]"></script>
<script>function recaptchaV2SubmitForm(response){return new Promise(function(){document.getElementById('[[+form_id]]').submit();})}</script>
<button type="submit" class="g-recaptcha" name="login" data-sitekey="[[+site_key]]" data-callback="recaptchaV2SubmitForm">[[+button_caption]]</button>