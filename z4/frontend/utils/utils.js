export function validateOnlyLetters(input) {
    const regex = /^[\p{L} '"`]+$/u
    return regex.test(input.trim())
}

export function validateEmail(input) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(input.trim())
}

export function validateYear(input) {
    const year = parseInt(input.trim(), 10);
    return (!isNaN(year) && year >= 1810 && year <= new Date().getFullYear())
}

export function isset(value) {
    return (
        value !== null &&
        value !== NaN &&
        value !== undefined &&
        value !== 'null' &&
        value !== 'null' &&
        value !== 'NULL' &&
        value !== 'nan' &&
        value !== 'NAN' &&
        value !== '') ? true : false;
}

export function comparePasses(pass, confirmPass) {
    return (pass === confirmPass) ? true : false;
}
export function validatePass(pass) {
    if (pass.length < 8) {
        return false;
    } else {
        return true
    }
}

export function replaceSpaces(str, symbol) {
    return str.replace(/\s/g, symbol);
}

export function showToast(title, message, type) {
    let color = ''
    switch (type) {
        case 'success':
            color = '#28a745'
            break;
        case 'warn':
            color = '#ffc107'
            break;
        case 'danger':
            color = '#dc3545'
            break;
        case 'info':
            color = '#17a2b8'
            break;
        default:
            color = '#007bff'
            break;
    }

    const now = new Date();
    const date = now.toDateString() + ' ' + now.toLocaleTimeString('en-US', { hour12: false });

    var toastHTML = `
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="position: fixed; 
        top: 20px; right: 20px; z-index: 1050;">
        <div class="toast-header d-flex justify-content-between">
            <svg class="bd-placeholder-img rounded mr-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
                role="img" aria-label=" :  " preserveAspectRatio="xMidYMid slice" focusable="false">
                <title> </title>
                <rect height="100%" fill=${color} width="100%">
                </rect><text x="50%" y="50%" fill="#dee2e6" dy=".3em"> </text>
            </svg>
            <div style="display: flex; flex-direction: column;">
            <strong class="mr-auto">${title}</strong>
            <small>${date}</small>
            </div>        
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    </div>
`;

    $('body').append(toastHTML);

    $('.toast').last().toast('show').on('hidden.bs.toast', function () {
        $(this).remove();
    });
}

export async function sessionCheckup() {
    fetch('https://node86.webte.fei.stuba.sk/api/session', {
        method: "GET",
        headers: {
            "Authorization": `Bearer ${sessionStorage.getItem('token')}`,
            "Content-type": "application/json; charset=UTF-8"
        }
    }).then(async response => {
        response.json()
            .then(sesStatus => {
                sessionStorage.setItem('session-status', sesStatus.logged_in ? 'active' : 'guest')
            })

    }).catch(error => {
        console.error('There has been a problem with checkup session:', error);
    })
}

export function removeQueryParam(paramToRemove) {
    const url = new URL(window.location);
    url.searchParams.delete(paramToRemove);
    history.replaceState(null, '', url);
}



