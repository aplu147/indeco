<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

$pageTitle = "Contact Us - Interia Decor";

// Process contact form
$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    $product = sanitize($_POST['product'] ?? '');
    
    // Validate inputs
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    
    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required';
    }
    
    if (empty($message)) {
        $errors['message'] = 'Message is required';
    }
    
    // If no errors, process the form
    if (empty($errors)) {
        // Prepare email content
        $to = getSetting('contact_email');
        $emailSubject = "New Contact Form Submission: $subject";
        
        $emailBody = "
            <html>
            <head>
                <title>$emailSubject</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #0C4B62; color: white; padding: 10px; text-align: center; }
                    .content { padding: 20px; border: 1px solid #ddd; }
                    .footer { margin-top: 20px; font-size: 0.8em; color: #777; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Interia Decor Contact Form</h2>
                    </div>
                    <div class='content'>
                        <p><strong>Name:</strong> $name</p>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>Phone:</strong> $phone</p>
                        <p><strong>Subject:</strong> $subject</p>
                        " . (!empty($product) ? "<p><strong>Product:</strong> $product</p>" : "") . "
                        <p><strong>Message:</strong></p>
                        <p>$message</p>
                    </div>
                    <div class='footer'>
                        <p>This email was sent from the contact form on " . SITE_URL . "</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        // Email headers
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: $name <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        
        // Send email
        if (mail($to, $emailSubject, $emailBody, $headers)) {
            $success = true;
            
            // Save to database
            $db->query('INSERT INTO contact_submissions (name, email, phone, subject, message, product, ip_address) 
                       VALUES (:name, :email, :phone, :subject, :message, :product, :ip)');
            $db->bind(':name', $name);
            $db->bind(':email', $email);
            $db->bind(':phone', $phone);
            $db->bind(':subject', $subject);
            $db->bind(':message', $message);
            $db->bind(':product', $product);
            $db->bind(':ip', $_SERVER['REMOTE_ADDR']);
            $db->execute();
        } else {
            $errors['email'] = 'Failed to send message. Please try again later.';
        }
    }
}
?>

<section class="page-header parallax-window" data-parallax="scroll" data-image-src="<?= SITE_URL ?>/assets/images/contact-bg.jpg">
    <div class="container">
        <h1>Contact Us</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contact</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h2 class="section-title mb-4">Get In Touch</h2>
                
                <?php if ($success): ?>
                <div class="alert alert-success">
                    <p>Thank you for your message! We'll get back to you soon.</p>
                </div>
                <?php else: ?>
                
                <form id="contact-form" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                    <input type="hidden" name="product" value="<?= htmlspecialchars($_GET['product'] ?? '') ?>">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name *</label>
                        <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                        <?php if (isset($errors['name'])): ?>
                        <div class="invalid-feedback"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?= $errors['email'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
                        <?php if (isset($errors['phone'])): ?>
                        <div class="invalid-feedback"><?= $errors['phone'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <select class="form-select" id="subject" name="subject">
                            <option value="General Inquiry" <?= ($_POST['subject'] ?? '') === 'General Inquiry' ? 'selected' : '' ?>>General Inquiry</option>
                            <option value="Project Inquiry" <?= ($_POST['subject'] ?? '') === 'Project Inquiry' ? 'selected' : '' ?>>Project Inquiry</option>
                            <option value="Product Inquiry" <?= ($_POST['subject'] ?? '') === 'Product Inquiry' ? 'selected' : '' ?>>Product Inquiry</option>
                            <option value="Service Inquiry" <?= ($_POST['subject'] ?? '') === 'Service Inquiry' ? 'selected' : '' ?>>Service Inquiry</option>
                            <option value="Feedback" <?= ($_POST['subject'] ?? '') === 'Feedback' ? 'selected' : '' ?>>Feedback</option>
                            <option value="Other" <?= ($_POST['subject'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Your Message *</label>
                        <textarea class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>" id="message" name="message" rows="5" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                        <?php if (isset($errors['message'])): ?>
                        <div class="invalid-feedback"><?= $errors['message'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="consent" name="consent" required>
                            <label class="form-check-label" for="consent">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">privacy policy</a> *
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
                
                <?php endif; ?>
            </div>
            
            <div class="col-lg-6">
                <div class="contact-info">
                    <h2 class="section-title mb-4">Contact Information</h2>
                    
                    <div class="contact-card mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Address</h4>
                            <p><?= getSetting('contact_address') ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-card mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Phone</h4>
                            <p><?= getSetting('contact_phone') ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-card mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p><?= getSetting('contact_email') ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-card mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Working Hours</h4>
                            <p>Sunday - Thursday: 9:00 AM - 6:00 PM</p>
                            <p>Friday: 9:00 AM - 1:00 PM</p>
                            <p>Saturday: Closed</p>
                        </div>
                    </div>
                    
                    <div class="contact-social">
                        <h4>Follow Us</h4>
                        <div class="social-links">
                            <a href="<?= getSetting('facebook_url') ?>" target="_blank" class="btn btn-outline-primary"><i class="fab fa-facebook-f"></i> Facebook</a>
                            <a href="#" class="btn btn-outline-danger"><i class="fab fa-instagram"></i> Instagram</a>
                            <a href="#" class="btn btn-outline-info"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-12">
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3618.041239510355!2d91.8652143150069!3d24.92802798402676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3750552a5e5a6e4d%3A0x5e5a6e4d3750552a!2sInteria%20Decor!5e0!3m2!1sen!2sbd!4v1620000000000!5m2!1sen!2sbd" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Privacy Policy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4>Information We Collect</h4>
                <p>We collect personal information that you voluntarily provide to us when you express an interest in obtaining information about us or our products and services, when you participate in activities on the website, or otherwise when you contact us.</p>
                
                <h4>How We Use Your Information</h4>
                <p>We use personal information collected via our website for a variety of business purposes described below. We process your personal information for these purposes in reliance on our legitimate business interests, in order to enter into or perform a contract with you, with your consent, and/or for compliance with our legal obligations.</p>
                
                <h4>Sharing Your Information</h4>
                <p>We only share information with your consent, to comply with laws, to provide you with services, to protect your rights, or to fulfill business obligations.</p>
                
                <h4>Data Security</h4>
                <p>We have implemented appropriate technical and organizational security measures designed to protect the security of any personal information we process.</p>
                
                <h4>Contact Us</h4>
                <p>If you have questions or comments about this policy, you may email us at <?= getSetting('contact_email') ?>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>