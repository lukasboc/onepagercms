<?php
$namefield = '';
$emailfield = '';
$messagefield = '';
$protectionFields = class_exists('SQLSpamProtectionActions')
    ? (new SQLSpamProtectionActions())->getFormFieldsHtml($section->getId()) : '';

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
echo '
                  <section class="' . $bgcolor . 'page-section" id="' . opcms_esc($section->getTitle()) . '" style="background-image: url(' . opcms_esc($section->getBackground()) . ')">
                        <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading text-uppercase">' . opcms_esc($section->getTitle()) . '</h2>
                <h3 class="section-subheading text-muted">' . opcms_esc($section->getMutedTitle()) . '</h3>
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
                        ' . $protectionFields . '
                        <div class="col-lg-12 text-center">
                            <button id="sendMessageButton" class="btn btn-primary btn-xl text-uppercase" type="submit">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </section>';
