// 1. Initialize EmailJS with your Public Key
(function() {
    // Replace 'YOUR_PUBLIC_KEY' with your actual EmailJS Public Key
    emailjs.init("JH-rnmDz_0bzscgyS");
})();

// 2. DOM Elements
const emailForm = document.getElementById('emailForm');
const submitBtn = document.getElementById('submitBtn');
const btnText = document.getElementById('btnText');
const btnSpinner = document.getElementById('btnSpinner');
const alertBox = document.getElementById('alertBox');

// 3. Form Submit Event Handler
emailForm.addEventListener('submit', function(event) {
    event.preventDefault();

    // Show loading state on button
    setLoadingState(true);

    // EmailJS parameters (must match template variables in EmailJS dashboard)
    const serviceID = 'service_u7xy8ga';  // Replace with your Service ID
    const templateID = 'template_sfodovc'; // Replace with your Template ID

    // Send the form directly
    emailjs.sendForm(serviceID, templateID, this)
        .then(() => {
            setLoadingState(false);
            showAlert('Email sent successfully! We will get back to you soon.', 'success');
            emailForm.reset(); // Clear input fields
        }, (error) => {
            setLoadingState(false);
            showAlert(`Failed to send email: ${error.text}`, 'danger');
            console.error('EmailJS Error:', error);
        });
});

// Helper: Toggle Loading Spinner
function setLoadingState(isLoading) {
    if (isLoading) {
        submitBtn.disabled = true;
        btnText.textContent = 'Sending...';
        btnSpinner.classList.remove('d-none');
    } else {
        submitBtn.disabled = false;
        btnText.textContent = 'Send Email';
        btnSpinner.classList.add('d-none');
    }
}

// Helper: Bootstrap Alert Handler
function showAlert(message, type) {
    alertBox.className = `alert alert-${type} alert-dismissible fade show`;
    alertBox.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    alertBox.classList.remove('d-none');
}