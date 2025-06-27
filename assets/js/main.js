document.addEventListener('DOMContentLoaded', function () {
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });

    // Flash message auto-dismiss
    const flashMessages = document.querySelectorAll('.alert-auto-dismiss');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => message.remove(), 500);
        }, 5000);
    });

    // Cart quantity controls
    document.querySelectorAll('.cart-quantity').forEach(input => {
        input.addEventListener('change', function () {
            const form = this.closest('form');
            if (form) form.submit();
        });
    });

    // Navbar functionality
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Desktop dropdown toggle
    const dropdowns = document.querySelectorAll('.group');

    dropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('button');
        const menu = dropdown.querySelector('.hidden');

        if (button && menu) {
            button.addEventListener('click', function () {
                menu.classList.toggle('hidden');
            });

            // Close when clicking outside
            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        }
    });

    const passwordFields = ['password', 'confirm_password', 'login_password'];

    passwordFields.forEach(id => {
        const input = document.getElementById(id);
        if (!input) return;

        // Get the toggle button using its unique ID or by querying its parent container
        const toggleButton =
            document.getElementById(`${id}_toggle`) ||
            input.parentElement.querySelector('button');

        if (!toggleButton) return;

        // Initially hide the button if input is empty
        toggleButton.style.display = input.value.trim() ? 'flex' : 'none';

        // Toggle visibility of button when typing
        input.addEventListener('input', function () {
            if (this.value.trim()) {
                toggleButton.style.display = 'flex';
            } else {
                toggleButton.style.display = 'none';
                this.type = 'password';
                setToggleIcon(toggleButton, 'hidden');
            }
        });

        // Toggle visibility on button click
        toggleButton.addEventListener('click', function (e) {
            e.preventDefault();
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            setToggleIcon(toggleButton, isHidden ? 'visible' : 'hidden');
        });
    });

    function setToggleIcon(button, state) {
        const svg = button.querySelector('svg');
        if (!svg) return;

        svg.innerHTML = state === 'visible'
            ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`
            : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
    }
});

