<script type="text/javascript" >
    function onloadCallback() {
        grecaptcha.render('[[+submitVar]]', {
            'sitekey' : '[[+site_key]]',
            'callback' : function (token) {
                document.getElementById('[[+submitVar]]').closest('form').submit();
            }
        });
    }
</script>
<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=[[+lang]]" async defer ></script>