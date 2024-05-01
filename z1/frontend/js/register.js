import { UserService } from "../service/UserService.js";
import { comparePasses, showToast, validateEmail, validateOnlyLetters, validatePass } from "./utils/utils.js";

const userServicer = new UserService('https://node86.webte.fei.stuba.sk/nobel-api');

const nameField = document.getElementById("name")
const surnameField = document.getElementById("surname")
const emailField = document.getElementById("email")
const passField = document.getElementById("pass")
const confPassField = document.getElementById("confPass")

window.addEventListener('load', e => {
    nameField.addEventListener("input", e => {
        if (validateOnlyLetters(nameField.value)) {
            nameField.classList.add('is-valid')
            nameField.classList.remove('is-invalid')

        } else {
            nameField.classList.remove('is-valid')
            nameField.classList.add('is-invalid')

        }
    })
    surnameField.addEventListener("input", e => {
        if (validateOnlyLetters(surnameField.value)) {
            surnameField.classList.add('is-valid')
            surnameField.classList.remove('is-invalid')

        } else {
            surnameField.classList.remove('is-valid')
            surnameField.classList.add('is-invalid')

        }
    })
    emailField.addEventListener("input", e => {
        if (validateEmail(emailField.value)) {
            emailField.classList.add('is-valid')
            emailField.classList.remove('is-invalid')

        } else {
            emailField.classList.remove('is-valid')
            emailField.classList.add('is-invalid')

        }
    })
    passField.addEventListener("input", e => {
        if (validatePass(passField.value)) {
            passField.classList.add('is-valid')
            passField.classList.remove('is-invalid')

            if (comparePasses(passField.value, confPassField.value)) {
                confPassField.classList.add('is-valid')
                confPassField.classList.remove('is-invalid')

            } else {
                confPassField.classList.remove('is-valid')
                confPassField.classList.add('is-invalid')

            }
        } else {
            passField.classList.remove('is-valid')
            passField.classList.add('is-invalid')

        }
    })
    confPassField.addEventListener("input", e => {
        if (validatePass(confPassField.value)) {
            confPassField.classList.add('is-valid')
            confPassField.classList.remove('is-invalid')

            if (comparePasses(passField.value, confPassField.value)) {
                confPassField.classList.add('is-valid')
                confPassField.classList.remove('is-invalid')

            } else {
                confPassField.classList.remove('is-valid')
                confPassField.classList.add('is-invalid')

            }
        } else {
            confPassField.classList.remove('is-valid')
            confPassField.classList.add('is-invalid')
        }
    })
    const regBtn = document.getElementById("register");

    regBtn.addEventListener('click', e => {
        if (
            validatePass(passField.value) &&
            validatePass(confPassField.value) &&
            comparePasses(passField.value, confPassField.value) &&
            validateOnlyLetters(nameField.value) &&
            validateOnlyLetters(surnameField.value) &&
            validateEmail(emailField.value)) {
            userServicer.register(nameField.value, surnameField.value, emailField.value, confPassField.value)
        } else {
            showToast('Fields error', 'Check registeration data fields.', 'warn')
        }
    })
})