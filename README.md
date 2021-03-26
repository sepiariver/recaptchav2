# ReCaptchaV2

[ReCaptchaV2 (version 3.x) integrates V2 AND V3 of Google's ReCaptcha service into MODX as a FormIt hook](https://sepiariver.com/modx/recaptchav2-supports-recaptchav3/).

It can also be used with the Login Extra as a preHook, as of version 1.0.0

Version 2+ updates the base Google Recaptcha class to the latest 1.1.2, including autoloading and cURL support. https://github.com/google/recaptcha

You must generate API keys for your domain here: [https://www.google.com/recaptcha/admin](https://www.google.com/recaptcha/admin)
and enter them into the System Settings before you can use ReCaptchaV2. **IMPORTANT**: You must choose V2 or V3 in the ReCaptcha admin, when generating your client keys. You can use both V2 and V3 on a single MODX install with this Extra—**there are separate system settings for V2 and V3**.

## Guide

For some quick start examples and implementation guide, go [here](https://sepiariver.com/modx/a-guide-to-recaptcha-v3-for-modx-cms/).

## Snippets

### recaptchav2

Designed to be used as a FormIt or Login hook. The hook stops form processing and returns an error if the Recaptcha challenge fails. Use with Google Recaptcha Version 2.

#### System Settings

- recaptchav2.site_key      Site key from Google. Required for front end. Default ''
- recaptchav2.secret_key    Secret key from Google. Required for back end API call. Default ''
- cultureKey                MODX culture key for language. Default 'en'

### recaptchav2_render

Renders the Recaptcha form element for Google Recaptcha Version 2 validation.

#### System Settings

- recaptchav2.site_key      Site key from Google. Required for front end. Default ''
- cultureKey                MODX culture key for language. Default 'en'

#### Snippet Properties

- tpl                       Template Chunk to use for rendering. Default 'recaptchav2_html'
- form_id                   String to use as ID attribute of recaptcha form. Default ''

### recaptchav3

Designed to be used as a FormIt or Login hook. The hook stops form processing and returns an error if the Recaptcha challenge fails. Use with Google Recaptcha Version 3.

#### System Settings

- recaptchav3.site_key      Site key from Google. Required for front end. Default ''
- recaptchav3.secret_key    Secret key from Google. Required for back end API call. Default ''
- cultureKey                MODX culture key for language. Default 'en'
- recaptchav3.action_key    Key to use for the action. See [this post](https://sepiariver.com/modx/recaptchav2-supports-recaptchav3/) for more information. Default 'recaptcha-action'
- recaptchav3.token_key     In V3 the token must be passed to the back end form processor. Default 'recaptcha-token'

#### Snippet Properties

- threshold                 Confidence threshold. If the confidence returned by Google's Recaptcha API response is below this value the Recaptcha will fail. Default 0.5
- display_resp_errors       Option to display API response errors. Default true

### recaptchav3_render

Renders the Recaptcha form element for Google Recaptcha Version 3 validation styles.

#### System Settings

- recaptchav2.site_key      Site key from Google. Required for front end. Default ''
- cultureKey                MODX culture key for language. Default 'en'
- recaptchav3.action_key    Key to use for the action. See [this post](https://sepiariver.com/modx/recaptchav2-supports-recaptchav3/) for more information. Default 'recaptcha-action'
- recaptchav3.token_key     In V3 the token must be passed to the back end form processor. Default 'recaptcha-token'

#### Snippet Properties

- tpl                       Template Chunk to use for rendering. Default 'recaptchav3_html'
- form_id                   String to use as ID attribute of recaptcha form. Default ''

## USAGE EXAMPLES:

### FormIt Hook

```
[[!FormIt?
   &hooks=`recaptchav3,email`
   ...
]]
```
(V3)

### Login Hook

```
[[!Login? &preHooks=`recaptchav2`]]
```
(V2)

### Render Snippet

You will also need to call the accompanying form element renderer snippet somewhere in your html form, for example:

```
<div class="form-item">
    [[!recaptchav3_render]]
    [[!+fi.error.recaptchav3_error]]
</div>
```
(V3)

As of 2.3+, you can use the "Invisible Recaptcha" implementation:

```
<form id="login-form">
[[!recaptchav2_render?
    &tpl=`recaptchav2_invisible_html`
    &form_id=`login-form`
]]
</form>
```
(V2)

In this usage, the "recaptchav2_invisible_html" Chunk renders a button with the necessary data attributes to trigger ReCaptcha. NOTE: the JavaScript implementation in the Chunk requires the `&form_id` to be defined.

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
