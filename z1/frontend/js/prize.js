const year = document.querySelector('#year')
const recName = document.querySelector('#receiver-full-name')
const catName = document.querySelector('#category-name')
const langSkName = document.querySelector('#language-sk')
const langEnName = document.querySelector('#language-en')
const genreSkName = document.querySelector('#genre-sk')
const genreEnName = document.querySelector('#genre-en')
const contrSkName = document.querySelector('#contribution-sk')
const contrEnName = document.querySelector('#contribution-en')

const readOnly = true



function setFieldsAccess(fields) {
    fields.forEach(field => {
        field.readOnly = readOnly
    });
}

function placePrize() {
    const currentPrize = JSON.parse(sessionStorage.getItem('currentPrize'));
    if (currentPrize) {
        year.value = currentPrize.year
        recName.value = `${currentPrize.receiver_name} ${currentPrize.receiver_surname}`
        catName.value = currentPrize.category_name
        langSkName.value = currentPrize.language_sk
        langEnName.value = currentPrize.language_en
        genreSkName.value = currentPrize.genre_sk
        genreEnName.value = currentPrize.genre_en
        contrSkName.value = currentPrize.contribution_sk
        contrEnName.value = currentPrize.contribution_en

        setFieldsAccess([
            year,
            recName,
            catName,
            langSkName,
            langEnName,
            genreSkName,
            genreEnName,
            contrSkName,
            contrEnName
        ])
    }

}


window.addEventListener('load', async e => {

    placePrize();


});
