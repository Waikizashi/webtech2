import { UserService } from "../service/UserService.js";
import { sessionCheckup } from "../js/utils/utils.js";

const userService = new UserService('https://node86.webte.fei.stuba.sk/nobel-app');

window.addEventListener('load', async e => {
    const logout = document.getElementById('logout');
    const logRef = document.getElementById('ref-login');
    await sessionCheckup().then(() => {
        if (sessionStorage.getItem('session-status') === 'active') {
            logout.hidden = false;
            logRef.hidden = true;
            logout.addEventListener('click', async e => {
                await userService.logout()
            })

        } else {
            logout.hidden = true;
            logRef.hidden = false;
        }
    })
})