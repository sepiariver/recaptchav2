<script src="https://www.google.com/recaptcha/api.js?render=[[+site_key]]&hl=[[++cultureKey]]"></script>
<input type="hidden" name="[[+token_name]]">
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('[[+site_key]]', {action: '[[+form_id]]'}).then(function(token) {
            document.querySelector('[name="[[+token_name]]"]').value = token;
        });
    });
</script>
