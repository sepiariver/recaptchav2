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

I tried making the render snippet usable as a preHook for FormIt but ran out of time. 

This Extra is maintained in Github: https://github.com/sepiariver/recaptchav2
Bug reports, comments and suggestions welcome.