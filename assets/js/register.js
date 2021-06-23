const registerForm = document.querySelector('.register-form');
const loginForm = document.querySelector('.login-form');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');



loginLink.addEventListener('click', () => {
    registerForm.className = 'login-form hide';
    loginForm.className = 'login-form show';
}) 

registerLink.addEventListener('click', () => {


    registerForm.className = 'login-form show';
    loginForm.className = 'login-form hide';
}) 
