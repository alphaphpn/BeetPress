<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Send Email - Tech Team</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- EmailJS SDK -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white text-center py-3">
          <h4 class="mb-0">Contact Support</h4>
          <small class="text-white-50">tech.team@sibugay.gov.ph</small>
        </div>
        <div class="card-body p-4">
          
          <!-- Alert Notification Box -->
          <div id="alertBox" class="alert d-none" role="alert"></div>

          <form id="emailForm">
            <input type="hidden" name="to_email" value="tech.team@sibugay.gov.ph">

            <!-- Sender Name -->
            <div class="mb-3">
              <label for="from_name" class="form-label font-weight-bold">Your Name</label>
              <input type="text" class="form-control" id="from_name" name="from_name" placeholder="John Doe" required>
            </div>

            <!-- Sender Email -->
            <div class="mb-3">
              <label for="from_email" class="form-label">Your Email</label>
              <input type="email" class="form-control" id="from_email" name="from_email" placeholder="name@example.com" required>
            </div>

            <!-- Email Subject -->
            <div class="mb-3">
              <label for="subject" class="form-label">Subject</label>
              <input type="text" class="form-control" id="subject" name="subject" placeholder="Inquiry topic..." required>
            </div>

            <!-- Message Body -->
            <div class="mb-3">
              <label for="message" class="form-label">Message</label>
              <textarea class="form-control" id="message" name="message" rows="4" placeholder="Write your message here..." required></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submitBtn" class="btn btn-primary w-100 py-2">
              <span id="btnText">Send Email</span>
              <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JavaScript -->
<script src="app.js"></script>
</body>
</html>