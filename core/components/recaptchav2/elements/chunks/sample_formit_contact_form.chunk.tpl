[[!FormIt?
   &hooks=`recaptchav2,email`
   &emailTpl=`sample_formit_contact_eml`
   &emailTo=`[[++emailsender]]`
   &emailSubject=`Contact from website`
   &validate=`name:required,
      email:email:required,
      text:stripTags`
   &successMessage=`<p class="button success">Success! Your enquiry has been sent.</p>`
]]
    <div class="sample_formit_contact_form">
        <h3>Contact Us</h3>
        [[!+fi.validation_error_message:notempty=`<div class="label alert">[[!+fi.validation_error_message]]</div>`]]
        [[!+fi.successMessage]]
        <form action="[[~[[*id]]]]" method="post" class="contact-form">
            <div class="form-item">
                <label for="name">
                    Name: *
                    [[!+fi.error.name:notempty=`<span class="error">[[!+fi.error.name]]</span>`]]
                </label>
                <input type="text" name="name" id="name" value="[[!+fi.name]]" />
            </div>
            <div class="form-item">
                <label for="email">
                    Email: *
                    [[!+fi.error.email:notempty=`<span class="error">[[!+fi.error.email]]</span>`]]
                </label>
                <input type="text" name="email" id="email" value="[[!+fi.email]]" />
            </div>
            <div class="form-item">
                <label for="text">
                    Message:
                    [[!+fi.error.text:notempty=`<span class="error">[[!+fi.error.text]]</span>`]]
                </label>
                <textarea name="text" id="text" value="[[!+fi.text]]">[[!+fi.text]]</textarea>
            </div>
            <div class="form-item">
                [[!recaptchav2_render]]
                [[!+fi.error.recaptchav2_error]]
            </div>
            <div class="form-button">
                <input type="submit" value="Send" class="button" />
            </div>
        </form>
    </div>