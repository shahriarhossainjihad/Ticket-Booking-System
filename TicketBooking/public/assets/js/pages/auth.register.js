/**
 *
 * AuthRegister
 *
 * Pages.Authentication.Register page content scripts. Initialized from scripts.js file.
 *
 *
 */

class AuthRegister {
	constructor() {
		// Initialization of the page plugins
		this._initForm();
	}

	// Form validation
	_initForm() {
		const form = document.getElementById('registerForm');
		if (!form) {
			return;
		}
		const validateOptions = {
			rules: {
				registerEmail: {
					required: true,
					email: true,
				},
				registerPassword: {
					required: true,
					minlength: 6,
					regex: /[a-z].*[0-9]|[0-9].*[a-z]/i,
				},
				registerName: {
					required: true,
				},
			},
			messages: {
				registerEmail: {
					email: 'Your email address must be in correct format!',
				},
				registerPassword: {
					minlength: 'Password must be at least {0} characters!',
					regex: 'Password must contain a letter and a number!',
				},
				registerName: {
					required: 'Please enter your name!',
				},
			},
		};
		jQuery(form).validate(validateOptions);
		form.addEventListener('submit', (event) => {
			event.preventDefault();
			event.stopPropagation();

			if (jQuery(form).valid()) {
				const formValues = {
					email: form.querySelector('[name="registerEmail"]').value,
					password: form.querySelector('[name="registerPassword"]').value,
					name: form.querySelector('[name="registerName"]').value,
				};

				// Perform AJAX request
				fetch('/api/register', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify(formValues),
				})
					.then((response) => response.json())
					.then((data) => {
						if (data.status === 200) {
							alert('Registration successful! Redirecting to login...');
							window.location.href = '/login';
						} else {
							alert('Registration failed: ' + (data.message || 'Unknown error.'));
						}
					})
					.catch((error) => console.error('Error:', error));
			}
		});
	}
}
