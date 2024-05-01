import { NobelService } from "../service/NobelService.js"
import { isset, showToast, validateOnlyLetters, validateYear } from "./utils/utils.js";
const nobelService = new NobelService('https://node86.webte.fei.stuba.sk/nobel-api');

const year = document.querySelector('#year')
const recName = document.querySelector('#name')
const recSurname = document.querySelector('#surname')
const recOrg = document.querySelector('#organization')
const sex = document.querySelector('#sex')
const birth = document.querySelector('#birth')
const death = document.querySelector('#death')
const catName = document.querySelector('#category-name')
const countryName = document.querySelector('#country-name')
const contrSkName = document.querySelector('#contribution-sk')
const contrEnName = document.querySelector('#contribution-en')
const langSkName = document.querySelector('#language-sk')
const langEnName = document.querySelector('#language-en')
const genreSkName = document.querySelector('#genre-sk')
const genreEnName = document.querySelector('#genre-en')

const saveBtn = document.getElementById('save')
const editBtn = document.getElementById('edit')
const deleteBtn = document.getElementById('delete')
const createBtn = document.getElementById('create')

let readOnly = true

function setFieldsAccess(fields) {
    fields.forEach(field => {
        field.disabled = readOnly
    });
}

function placeReceiver(currentReceiver) {
    if (currentReceiver) {
        recName.value = currentReceiver.name
        recSurname.value = currentReceiver.surname
        recOrg.value = currentReceiver.organization
        year.value = currentReceiver.year
        sex.value = currentReceiver.sex
        birth.value = currentReceiver.birth
        death.value = currentReceiver.death
        catName.value = currentReceiver.category_name
        countryName.value = currentReceiver.country_name
        langSkName.value = currentReceiver.language_sk
        langEnName.value = currentReceiver.language_en
        genreSkName.value = currentReceiver.genre_sk
        genreEnName.value = currentReceiver.genre_en
        contrSkName.value = currentReceiver.contribution_sk
        contrEnName.value = currentReceiver.contribution_en
    }
}

function validateFileds() {
    let valid = true;
    if (validateYear(year.value) && isset(year.value)) {
        year.classList.add('is-valid')
        year.classList.remove('is-invalid')
    } else {
        year.classList.remove('is-valid')
        year.classList.add('is-invalid')
        valid = false;
    }
    if (validateOnlyLetters(recName.value) && isset(recName.value)) {
        recName.classList.add('is-valid')
        recName.classList.remove('is-invalid')
    } else {
        recName.classList.remove('is-valid')
        recName.classList.add('is-invalid')
        valid = false;
    }
    if (validateOnlyLetters(recSurname.value) && isset(recSurname.value)) {
        recSurname.classList.add('is-valid')
        recSurname.classList.remove('is-invalid')
    } else {
        recSurname.classList.remove('is-valid')
        recSurname.classList.add('is-invalid')
        valid = false;
    }
    if (validateOnlyLetters(catName.value) && isset(catName.value)) {
        catName.classList.add('is-valid')
        catName.classList.remove('is-invalid')
    } else {
        catName.classList.remove('is-valid')
        catName.classList.add('is-invalid')
        valid = false;
    }
    if (validateOnlyLetters(countryName.value) && isset(countryName.value)) {
        countryName.classList.add('is-valid')
        countryName.classList.remove('is-invalid')
    } else {
        countryName.classList.remove('is-valid')
        countryName.classList.add('is-invalid')
        valid = false;
    }
    return valid
}

window.addEventListener('load', e => {
    const currentReceiver = JSON.parse(sessionStorage.getItem('currentReceiver'));
    editBtn.addEventListener('click', e => {
        save.disabled = false
        save.hidden = false
    })

    const newPrize = currentReceiver.new;
    if (newPrize === true) {
        if (sessionStorage.getItem('session-status') === 'active') {
            createBtn.disabled = false
            createBtn.hidden = false
            deleteBtn.disabled = false
        } else {
            window.location.href = '/nobel-app/login?unauthorized=1'
        }
    } else if (newPrize === false) {
        editBtn.hidden = false
        editBtn.disabled = false
        deleteBtn.disabled = false
        setFieldsAccess([
            year,
            recName,
            recSurname,
            recOrg,
            sex,
            birth,
            death,
            catName,
            countryName,
            langSkName,
            langEnName,
            genreSkName,
            genreEnName,
            contrSkName,
            contrEnName
        ])
        placeReceiver(currentReceiver);
    } else {
        showToast("Data error", "Missing receiver data...", 'danger');
    }
    saveBtn.addEventListener('click', e => {
        save.disabled = false
        if (validateFileds()) {
            nobelService.updatePrize(
                {
                    id: currentReceiver.id,
                    name: recName.value,
                    surname: recSurname.value,
                    organization: recOrg.value,
                    sex: sex.value,
                    birth: birth.value,
                    death: death.value,
                    year: year.value,
                    contributionSk: contrSkName.value,
                    contributionEn: contrEnName.value,
                    categoryName: catName.value,
                    countryName: countryName.value,
                    languageSk: langSkName.value,
                    languageEn: langEnName.value,
                    genreSk: genreSkName.value,
                    genreEn: genreEnName.value
                }
            )
            readOnly = true
            setFieldsAccess([
                year,
                recName,
                recSurname,
                recOrg,
                sex,
                birth,
                death,
                catName,
                countryName,
                langSkName,
                langEnName,
                genreSkName,
                genreEnName,
                contrSkName,
                contrEnName
            ])
            save.disabled = true
        } else {
            showToast('Fields error', 'Check prize data fields.', 'warn')
        }

    })
    editBtn.addEventListener('click', e => {
        readOnly = false
        setFieldsAccess([
            year,
            recName,
            recSurname,
            recOrg,
            sex,
            birth,
            death,
            catName,
            countryName,
            langSkName,
            langEnName,
            genreSkName,
            genreEnName,
            contrSkName,
            contrEnName
        ])
    })
    deleteBtn.addEventListener('click', e => {
        console.log(currentReceiver)
        nobelService.deletePrize(currentReceiver.id)
    })
    createBtn.addEventListener('click', e => {
        validateFileds() ?
            nobelService.createPrize(
                {
                    name: recName.value,
                    surname: recSurname.value,
                    organization: recOrg.value,
                    sex: sex.value,
                    year: year.value,
                    birth: birth.value,
                    death: death.value,
                    contributionSk: contrSkName.value,
                    contributionEn: contrEnName.value,
                    categoryName: catName.value,
                    countryName: countryName.value,
                    languageSk: langSkName.value,
                    languageEn: langEnName.value,
                    genreSk: genreSkName.value,
                    genreEn: genreEnName.value
                }
            )
            :
            showToast('Fields error', 'Check receiver data fields.', 'warn')

    })
});

