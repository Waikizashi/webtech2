import { showToast, isset } from "../js/utils/utils.js";

export class UserService {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    async getAuthUrl() {
        try {
            var url = `${this.baseUrl}/auth-url`;

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            return data.url
        } catch (error) {
            console.error("AUTH-URL-: ", error);
            return null;
        }
    }

    async authByCode(code) {
        try {
            var url = `${this.baseUrl}/login?code=${code}`;
            await fetch(url).then(async response => {
                const authData = await response.json()
                sessionStorage.setItem('session-status', 'active')
                sessionStorage.setItem('user', authData.authInfo.user)
                window.location.href = '/nobel-app?logged=true'
            })
        } catch (error) {
            console.error("AUTH-ERROR: ", error);
            return null;
        }
    }
    async authByLoginAndPass(login, pass) {
        try {
            var url = `${this.baseUrl}/login`;
            const loginBody = JSON.stringify({
                email: login,
                pass: pass
            })
            const response = await fetch(url, {
                method: "POST",
                body: loginBody,
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            })
            const authData = await response.json()
            if(authData.authInfo.authenticated === true){
                sessionStorage.setItem('session-status', 'active')
                sessionStorage.setItem('user', authData.authInfo.user)
                window.location.href = '/nobel-app?logged=true'
            }else{
                showToast('Login ERROR', authData.authInfo.error, 'danger')
            }
        } catch (error) {
            console.error("AUTH-ERROR: ", error);
            return null;
        }
    }
    async register(name, surname, email, pass) {
        try {
            var url = `${this.baseUrl}/reg`;

            if (!isset(name) || !isset(surname) || !isset(email) || !isset(pass)) {
                console.error("Reginfo missing: ", url);
                return null
            }
            const regBody = JSON.stringify({
                name: name,
                surname: surname,
                email: email,
                pass: pass
            })
            const response = await fetch(url, {
                method: "POST",
                body: regBody,
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const regData = await response.json()
            if(regData.regInfo.authenticated === true){
                sessionStorage.setItem('session-status', 'active')
                sessionStorage.setItem('user', regData.regInfo.user)
                window.location.href = '/nobel-app?registred=true'
            }else{
                showToast('Registration ERROR', regData.regInfo.error, 'danger')
            }
        } catch (error) {
            return ({'reg-error':"Could not register new user: " + error});
        }
    }
    async logout() {
        try {
            var url = `${this.baseUrl}/logout`;
            await fetch(url).then(() => {
                sessionStorage.setItem('session-status', 'guest')
                sessionStorage.setItem('user', undefined)
            });
            window.location.href = '/nobel-app?logged=false'

        } catch (error) {
            console.error("logout-ERROR: ", error);
            return null;
        }
    }
}