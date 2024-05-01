import { NobelService } from "../service/NobelService.js";
let showAll = false;
let page = 1;
let perpage = 10;
let sortBy = '';
let sortMethod = "ASC";
let filterOptions = { filter: null, value: null };


const nobelService = new NobelService('https://node86.webte.fei.stuba.sk/nobel-api')

function placeRows(list, tableBody) {
    const tableHeadYear = document.querySelector("#main-table #year")
    const tableHeadCategory = document.querySelector("#main-table #category")
    if (filterOptions.filter === 'year') {
        tableHeadYear.style.display = 'none'
        tableHeadCategory.style.display = ''
    } else if (filterOptions.filter === 'category') {
        tableHeadCategory.style.display = 'none'
        tableHeadYear.style.display = ''
    } else {
        tableHeadYear.style.display = ''
        tableHeadCategory.style.display = ''
    }
    list.data.forEach($prize => {
        let recTitle = ''

        if ($prize.receiver_organization !== 'NULL' && $prize.receiver_organization !== '') {
            recTitle = $prize.receiver_organization
        } else {
            recTitle = `${$prize.receiver_name} ${$prize.receiver_surname}`
        }

        if (filterOptions.filter === 'year') {
            const row = `<tr>
                        <td><p data-rec-id="${$prize.receiver_id}" class="rec-name">${recTitle}</p>
                        <span data-prize-id="${$prize.id}" class="open-row badge badge-info">show more</span>
                        </td>                     
                        <td>${$prize.category_name}</td>                     
                     </tr>`;
            tableBody.innerHTML += row;
        }
        else if (filterOptions.filter === 'category') {
            const row = `<tr">
                        <td><p data-rec-id="${$prize.receiver_id}" class="rec-name">${recTitle}</p>
                        <span data-prize-id="${$prize.id}" class="open-row badge badge-info">show more</span>
                        </td>                     
                        <td>${$prize.year}</td>
                     </tr>`;
            tableBody.innerHTML += row;
        } else {
            const row = `<tr>
                        <td><p data-rec-id="${$prize.receiver_id}" class="rec-name">${recTitle}</p>
                        <span data-prize-id="${$prize.id}" class="open-row badge badge-info">show more</span>
                        </td>
                        <td>${$prize.year}</td>                        
                        <td>${$prize.category_name}</td>                        
                     </tr>`;
            tableBody.innerHTML += row;
        }
    });
}

async function placePrizes() {
    const spinnerBox = document.getElementById('spinner-box');
    const mainTable = document.getElementById('main-table');
    spinnerBox.classList.remove('hide')
    mainTable.classList.add('hide')
    switch (sortBy) {
        case "name":
            await placePrizesSortedByName()
            break;
        case "year":
            await placePrizesSortedByYear()
            break;
        case "category":
            await placePrizesSortedByCategory()
            break;
        default:
            await placePrizesManual()
            break;
    }

    spinnerBox.classList.add('hide')
    mainTable.classList.remove('hide')
    const infoEls = document.querySelectorAll('#main-table tbody tr .open-row');
    infoEls.forEach($infoEl => {
        $infoEl.addEventListener('click', function (e) {
            openPrize($infoEl.dataset.prizeId)
        })
    })
    const recNames = document.querySelectorAll(" .rec-name");
    recNames.forEach($recName => {
        $recName.addEventListener('click', e => {
            openNobelOwner($recName.dataset.recId);
        })
    })
}

async function placePrizesManual() {
    const tableBody = document.querySelector("#main-table tbody")
    tableBody.innerHTML = null;
    const nobelsList = await nobelService.getPrizes(page, perpage, filterOptions);
    placeRows(nobelsList, tableBody);
}

async function placePrizesSortedByYear() {
    const tableBody = document.querySelector("#main-table tbody")
    tableBody.innerHTML = null;
    const nobelsList = await nobelService.getPrizesSortedByYear(page, perpage, sortMethod, filterOptions);
    placeRows(nobelsList, tableBody);
}

async function placePrizesSortedByName() {
    const tableBody = document.querySelector("#main-table tbody")
    tableBody.innerHTML = null;
    const nobelsList = await nobelService.getPrizesSortedByName(page, perpage, sortMethod, filterOptions);
    placeRows(nobelsList, tableBody);
}

async function placePrizesSortedByCategory() {
    const tableBody = document.querySelector("#main-table tbody")
    tableBody.innerHTML = null;
    const nobelsList = await nobelService.getPrizesSortedByCategory(page, perpage, sortMethod, filterOptions);
    placeRows(nobelsList, tableBody);
}

async function openPrize(prizeId) {
    sessionStorage.removeItem('currentPrize')
    console.log(prizeId)
    const prize = await nobelService.getPrize(prizeId)
    sessionStorage.setItem('currentPrize', JSON.stringify(prize.data));
    window.location.href = '/nobel-app/prize';

}
async function openNobelOwner(recId) {
    const rec = await nobelService.getReceiverById(recId)
    rec.data.new = false;
    sessionStorage.setItem('currentReceiver', JSON.stringify(rec.data));
    window.location.href = '/nobel-app/nobel-receiver';

}

window.addEventListener('load', async e => {

    sessionStorage.removeItem('currentPrize')
    sessionStorage.removeItem('currentReceiver')
    placePrizes();

    const bNext = document.getElementById('next');
    const bPrev = document.getElementById('prev');
    const pageNum = document.getElementById('pagenum');

    pageNum.innerHTML = `${page}`

    const resetFilterBtn = document.getElementById('reset-filters')
    const showAllBtn = document.getElementById('show-all')

    const nameSortEl = document.getElementById('name');
    const yearSortEl = document.getElementById('year');
    const categorySortEl = document.getElementById('category');

    const categoryFilter = document.querySelector('#category-filter')
    const yearFilter = document.querySelector('#year-filter')

    const categories = await nobelService.getCategories()

    categories.data.forEach($category => {
        const categoryOpt = `<option>${$category.category_name}</option>`;
        categoryFilter.innerHTML += categoryOpt
    })

    resetFilterBtn.addEventListener('click', e => {
        filterOptions.filter = null
        filterOptions.value = null
        placePrizes()
    })

    categoryFilter.addEventListener('change', e => {
        filterOptions.filter = 'category'
        filterOptions.value = categoryFilter.value
        if (!['Category'].includes(filterOptions.value)) {
            yearFilter.value = '';
            placePrizes()
        } else {
            filterOptions.filter = null
            filterOptions.value = null
        }
    })

    showAllBtn.addEventListener('click', e => {
        const pageItems = document.querySelectorAll(".page-item")
        if (showAll) {
            showAllBtn.innerHTML = "show all"
            page = 1;
            perpage = 10;
        } else {
            showAllBtn.innerHTML = "show by pages"
            page = null;
            perpage = null;
        }
        showAll = !showAll;
        pageItems.forEach(pageItem => {
            pageItem.style.display = showAll ? 'none' : '';
        })
        placePrizes();
    })

    yearFilter.addEventListener('keypress', e => {
        if (e.key === "Enter") {
            filterOptions.value = parseInt(yearFilter.value)
            if (yearFilterValidate(filterOptions)) {
                categoryFilter.value = 'Category'
                yearFilter.classList.add('is-valid')
                yearFilter.classList.remove('is-invalid')
            }
            else {
                yearFilter.classList.add('is-invalid')
                yearFilter.classList.remove('is-valid')
            }
            placePrizes()
        }
    })

    bNext.addEventListener('click', e => {
        page++;
        pageNum.innerHTML = `${page}`
        placePrizes();
    })


    bPrev.addEventListener('click', e => {
        if (page > 1) {
            page--;
            pageNum.innerHTML = `${page}`
            placePrizes();
        }
    })

    nameSortEl.addEventListener('click', e => {
        sortMethod = sortMethod === "ASC" ? "DESC" : "ASC"
        sortBy = 'name'
        placePrizes()
    })
    yearSortEl.addEventListener('click', e => {
        sortMethod = sortMethod === "ASC" ? "DESC" : "ASC"
        sortBy = 'year'
        placePrizes()
    })
    categorySortEl.addEventListener('click', e => {
        sortMethod = sortMethod === "ASC" ? "DESC" : "ASC"
        sortBy = 'category'
        placePrizes()
    })
});

function yearFilterValidate(filterOptions) {
    if (filterOptions.value && filterOptions.value > 1900 && typeof (filterOptions.value) === "number" && filterOptions.value !== NaN) {
        filterOptions.filter = 'year'
        return true;
    }
    else {
        filterOptions.filter = null
        filterOptions.value = null
        return false;
    }
}


