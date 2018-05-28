# ReCaptchaV2
ReCaptchaV2 integrates Version 2 of Google's ReCaptcha service into MODX as a FormIt hook.

It can also be used with the Login Extra as a preHook, as of version 1.0.0

Version 2+ updates the base Google Recaptcha class to the latest 1.1.2, including autoloading and cURL support. https://github.com/google/recaptcha

You must generate API keys for your domain here: [https://www.google.com/recaptcha/admin](https://www.google.com/recaptcha/admin)
and enter them into the System Settings before you can use ReCaptchaV2.

###USAGE EXAMPLE:

```
[[!FormIt?
   &hooks=`recaptchav2,email`
   ...
]]
```

OR
```
[[!Login? &preHooks=`recaptchav2`]]
```

You will also need to call the accompanying form element renderer snippet somewhere in your html form, for example:

```
<div class="form-item">
    [[!recaptchav2_render]]
    [[!+fi.error.recaptchav2_error]]
</div>
```

As of 2.3+, you can use the "Invisible Recaptcha" implementation:

```
<form id="login-form">
[[!recaptchav2_render?
    &tpl=`recaptchav2_invisible_html`
    &form_id=`login-form`
    &button_caption=`Submit this form`
]]
</form>
```
In this usage, the recaptchav2_invisible_html renders a button with the necessary data attributes to trigger Recaptcha.

The render snippet may or may not be usable as a preHook for FormIt at this time. 

This Extra is maintained in Github: https://github.com/sepiariver/recaptchav2
Bug reports, comments and suggestions welcome.
