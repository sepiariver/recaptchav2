# ReCaptchaV2
ReCaptchaV2 (version 3.x) integrates V2 AND V3 of Google's ReCaptcha service into MODX as a FormIt hook.

It can also be used with the Login Extra as a preHook, as of version 1.0.0

Version 2+ updates the base Google Recaptcha class to the latest 1.1.2, including autoloading and cURL support. https://github.com/google/recaptcha

You must generate API keys for your domain here: [https://www.google.com/recaptcha/admin](https://www.google.com/recaptcha/admin)
and enter them into the System Settings before you can use ReCaptchaV2. **IMPORTANT**: You must choose V2 or V3 in the ReCaptcha admin, when generating your client keys. You can use both V2 and V3 on a single MODX install with this Extra--**there are separate system settings for V2 and V3**.

### USAGE EXAMPLES:

```
[[!FormIt?
   &hooks=`recaptchav3,email`
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
    [[!recaptchav3_render]]
    [[!+fi.error.recaptchav3_error]]
</div>
```

As of 2.3+, you can use the "Invisible Recaptcha" implementation:

```
<form id="login-form">
[[!recaptchav2_render?
    &tpl=`recaptchav2_invisible_html`
    &form_id=`login-form`
    &button_caption=`Submit`
]]
</form>
```
In this usage, the "recaptchav2_invisible_html" Chunk renders a button with the necessary data attributes to trigger ReCaptcha. NOTE: the JavaScript implementation in the Chunk requires the `&form_id` to be defined. However, `&button_caption` is optional and defaults to `Login`.

As of 3.1+, support for multiple forms in RecaptchaV3 is improved. The threshold for a passing verification score can be customized per Snippet call with the `recaptchav3.threshold` property.

```
<h2>Form Test 1</h2>
[[!FormIt?
    &hooks=`recaptchav3,FormItSaveForm`
    &validate=`testing1:required:minLength=^12^`
    &formName=`form-test-1`
    &recaptchav3.token_key=`token-1`
    &recaptchav3.action_key=`action-1`
    &recaptchav3.threshold=`0.9`
    &submitVar=`submit1`
]]

<form action="[[~[[*id]]? &scheme=`full`]]" method="POST">
    [[!+fi.error.testing1]]
    <input type="text" name="testing1" value="[[!+fi.testing1]]">
    <input type="submit" name="submit1" value="submit">
    [[!recaptchav3_render?
        &tpl=`recaptchav3_html`
        &token_key=`token-1`
        &action_key=`action-1`
    ]]
    [[!+fi.error.recaptchav3_error]]
</form>

<h2>Form Test 2</h2>
[[!FormIt?
    &hooks=`recaptchav3,FormItSaveForm`
    &validate=`testing2:required:minLength=^12^`
    &formName=`form-test-2`
    &recaptchav3.token_key=`token-2`
    &recaptchav3.action_key=`action-2`
    &recaptchav3.threshold=`0.5`
    &submitVar=`submit2`
]]

<form action="[[~[[*id]]? &scheme=`full`]]" method="POST">
    [[!+fi.error.testing2]]
    <input type="text" name="testing2" value="[[!+fi.testing2]]">
    <input type="submit" name="submit2" value="submit">
    [[!recaptchav3_render?
        &tpl=`recaptchav3_html`
        &token_key=`token-2`
        &action_key=`action-2`
    ]]
    [[!+fi.error.recaptchav3_error]]
</form>
```

The render snippet may or may not be usable as a preHook for FormIt at this time. NOTE: you can customize the behaviour of your ReCaptcha implementation, in accordance with [Google's developer documentation](https://developers.google.com/recaptcha/intro), by customizing the Chunks used for rendering. You can specify any Chunk in the `&tpl` property of the "recaptchav{n}_render" Snippets.

This Extra is maintained in Github: https://github.com/sepiariver/recaptchav2
Bug reports, comments and suggestions welcome.
