import { validateEmail, isset, showToast, validatePass } from "./utils/utils.js";
import { UserService } from "../service/UserService.js";


const userService = new UserService('https://node86.webte.fei.stuba.sk/nobel-api');

const googleAuthButton = document.getElementById('google-auth')

const params = new URLSearchParams(window.location.search);
const code = params.get('code');
const unauthorized = params.get('unauthorized');

window.addEventListener('load', e => {
    const loginButton = document.getElementById('login')
    const emailField = document.getElementById("email");
    const passField = document.getElementById("pass");

    emailField.addEventListener("change", () => {
        if (validateEmail(emailField.value)) {
            emailField.classList.add('is-valid')
            emailField.classList.remove('is-invalid')

        } else {
            emailField.classList.remove('is-valid')
            emailField.classList.add('is-invalid')

        }
    });
    passField.addEventListener("change", () => {
        if (validatePass(passField.value)) {
            passField.classList.add('is-valid')
            passField.classList.remove('is-invalid')

        } else {
            passField.classList.remove('is-valid')
            passField.classList.add('is-invalid')

        }
    });

    loginButton.addEventListener('click', e => {
        if (validateEmail(emailField.value) && validatePass(passField.value)) {
            userService.authByLoginAndPass(emailField.value, passField.value)
        } else {
            showToast('Fields error', 'Check login data fields.', 'warn')
        }
    })
});

if (unauthorized === '1') {
    showToast('Unauthorized', "You can't access this page unless you are logged in.", 'warn');
}
if (isset(code)) {
    userService.authByCode(code)
} else {
    const googleAuthUrl = await userService.getAuthUrl();
    googleAuthButton.addEventListener('click', e => {
        window.location.href = googleAuthUrl
    })
}
