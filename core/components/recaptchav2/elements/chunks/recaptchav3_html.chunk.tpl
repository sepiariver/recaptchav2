<script src="https://www.google.com/recaptcha/api.js?render=[[+site_key]]&hl=[[++cultureKey]]"></script>
<input type="hidden" name="recaptcha-action" value="[[+form_id]]">
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('[[+site_key]]', {action: '[[+form_id]]'});
    });
</script>
