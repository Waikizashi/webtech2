import { showToast, removeQueryParam } from "./utils/utils.js";

const params = new URLSearchParams(window.location.search);
const logged = params.get('logged');
const registred = params.get('registred');
if (logged) {
    if (logged === 'true') {
        showToast('Authorization success', "You're successfuly logged in", 'success');
    } else if (logged === 'false') {
        showToast('Logout', "You're successfuly logged out", 'danger');
    }
    removeQueryParam('logged');
}
if (registred) {
    if (registred === 'true') {
        showToast('Authorization success', "You're successfuly registred in", 'success');
    }
    removeQueryParam('registred');
}