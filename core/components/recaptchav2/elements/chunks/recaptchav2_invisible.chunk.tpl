<script type="text/javascript" >
    function onSubmit(token) {
        return true;
    }
    var onloadCallback = function() {
        grecaptcha.render('submit', {
            'sitekey' : '[[++site_key]]',
            'callback' : onSubmit
        });
    };
</script>
<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=[[++cultureKey]]" async defer ></script>