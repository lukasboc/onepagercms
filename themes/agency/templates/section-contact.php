<?php
$settingsActions = new SQLSettingActions();
$namefield = '';
$emailfield = '';
$messagefield = '';
$captchafield = '';

if ($section->getName()) {
    $namefield = '
                            <div class="form-group">
                                <input class="form-control" id="name" name="Name" type="text" placeholder="Your Name *" required="required" data-validation-required-message="Please enter your name.">
                                <p class="help-block text-danger"></p>
                            </div>
                            ';
}
if ($section->getEmail()) {
    $emailfield = '
                            <div class="form-group">
                                <input class="form-control" id="email" name="Mail" type="email" placeholder="Your Email *" required="required" data-validation-required-message="Please enter your email address.">
                                <p class="help-block text-danger"></p>
                            </div>
                            ';
}
if ($section->getMessage()) {
    $messagefield = '
                           <div class="form-group">
                                <textarea class="form-control" name="Message" rows="8" id="message" placeholder="Your Message *" required="required" data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                            ';
}
if ($section->getCaptcha()) {
    $captchafield = '
                           <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                           <div class="g-recaptcha" data-sitekey="' . $settingsActions->getSettingValue('recaptcha_key') . '"></div>';
}
echo '
                  <section class="' . $bgcolor . 'page-section" id="' . $section->getTitle() . '" style="background-image: url(' . $section->getBackground() . ')">
                        <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading text-uppercase">' . $section->getTitle() . '</h2>
                <h3 class="section-subheading text-muted">' . $section->getMutedTitle() . '</h3>
                <div class="text-center mb-5">' . $section->getText() . '

      </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <form id="contactForm" name="sentMessage" action="../misc/contactform.php" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            ' . $namefield . $emailfield . '
                        </div>
                        <div class="col-md-6">
                            ' . $messagefield . '
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" name="contactId" value="' . $section->getId() . '">
                        <div class="col-lg-12 text-center">
                            <div class="text-center mb-1" style="width:304px; margin: 0 auto">' . $captchafield . '</div>
                            <button id="sendMessageButton" class="btn btn-primary btn-xl text-uppercase" type="submit">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </section>';
