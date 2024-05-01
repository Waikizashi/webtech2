import { BachelorsService } from "../../service/BachelorsService.js";
import { isset } from "../../utils/utils.js"

const bachelorsService = new BachelorsService('https://node86.webte.fei.stuba.sk/parse-api')

const subTable = () => document.querySelector('#sub-table')
const mainTable = () => document.querySelector('#main-table')
const backBtn = () => document.querySelector('#back-btn')
const updbtn = () => document.querySelector('#update')

const createTableRow = (dataRow) => {
    const row = document.createElement('tr');
    row.style.cursor = 'pointer';
    row.style.userSelect = 'none';
    row.innerHTML = `
      <td>${dataRow.Typ}</td>
      <td>${dataRow["Názov témy"]}</td>
      <td>${dataRow["Vedúci práce"]}</td>
      <td>${dataRow["Garantujúce pracovisko"]}</td>
      <td>${dataRow.Program}</td>
      <td>${dataRow.Zameranie}</td>
      <td>${dataRow["Určené pre"]}</td>
      <td>${dataRow["Obsadené/Max"]}</td>
      <td>${dataRow.Riešitelia}</td>
    `;

    const toggleDetails = (e) => {
        const existingDetails = document.querySelector('.details-popover');
        if (existingDetails) {
            existingDetails.remove();
        }

        if (row === e.target.closest('tr')) {
            const detailsDiv = document.createElement('div');
            detailsDiv.className = 'details-popover';
            detailsDiv.style.position = 'absolute';
            detailsDiv.style.top = `${row.offsetTop + row.offsetHeight}px`;
            detailsDiv.style.left = `5%`;
            detailsDiv.textContent = dataRow.Abstrakt;


            document.querySelector('body').appendChild(detailsDiv);

            const closePopover = (event) => {
                document.querySelector('body').removeChild(detailsDiv)
            };
            detailsDiv.addEventListener('click', closePopover, { once: true });
        }
    };


    row.addEventListener('click', toggleDetails);

    return row;
};
async function placeBachelors(bId, filterValue, filterType) {
    document.querySelector('#pracaType').hidden = true
    document.querySelector('#filter').hidden = false
    backBtn().hidden = false
    const pracaType = document.querySelector('input[name="pracaType"]:checked').value;
    subTable().hidden = true
    mainTable().hidden = false
    const tbody = document.querySelector('#main-table tbody')
    if (isset(filterValue)) {
        const cachedInfo = sessionStorage.getItem(bId);
        if (cachedInfo) {
            const bachelors = JSON.parse(cachedInfo)
            bachelors.forEach(dataRow => {
                if (dataRow.Typ === pracaType || pracaType === 'Non specified') {
                    switch (filterType) {
                        case 'abst-name':
                            if (dataRow.Abstrakt.includes(filterValue) || dataRow["Názov témy"].includes(filterValue)) {
                                tbody.appendChild(createTableRow(dataRow));
                            }
                            break;
                        case 'prog-ved':
                            if (dataRow["Vedúci práce"].includes(filterValue) || dataRow.Program.includes(filterValue)) {
                                tbody.appendChild(createTableRow(dataRow));
                            }
                            break;
                        default:
                            break;
                    }
                }
            });
        } else {
            const bachelors = await bachelorsService.getBachelors(bId)
            sessionStorage.setItem(`${bId}`, JSON.stringify(bachelors))
            bachelors.forEach(dataRow => {
                if (dataRow.Typ === pracaType || pracaType === 'Non specified') {
                    switch (filterType) {
                        case 'abst-name':
                            if (dataRow.Abstrakt.includes(filterValue) || dataRow["Názov témy"].includes(filterValue)) {
                                tbody.appendChild(createTableRow(dataRow));
                            }
                            break;
                        case 'prog-ved':
                            if (dataRow["Vedúci práce"].includes(filterValue) || dataRow.Program.includes(filterValue)) {
                                tbody.appendChild(createTableRow(dataRow));
                            }
                            break;
                        default:
                            break;
                    }
                }
            });
        }
    } else {
        const cachedInfo = sessionStorage.getItem(bId);
        if (cachedInfo) {
            const bachelors = JSON.parse(cachedInfo)
            bachelors.forEach(dataRow => {
                if (dataRow.Typ === pracaType || pracaType === 'Non specified') {
                    tbody.appendChild(createTableRow(dataRow));
                }
            });
        } else {
            const bachelors = await bachelorsService.getBachelors(bId)
            sessionStorage.setItem(`${bId}`, JSON.stringify(bachelors))
            bachelors.forEach(dataRow => {
                if (dataRow.Typ === pracaType || pracaType === 'Non specified') {
                    tbody.appendChild(createTableRow(dataRow));
                }
            });
        }
    }

}

function cleanMainTable() {
    const tbody = document.querySelector('#main-table tbody')
    tbody.innerHTML = ''
    subTable().hidden = false
    mainTable().hidden = true
    backBtn().hidden = true
}

window.addEventListener('load', () => {
    const ids = document.querySelectorAll('#sub-table tbody td:nth-child(2)')
    document.querySelector('#filter-input').addEventListener('input', e => {
        const currentBId = sessionStorage.getItem('currentBId')
        cleanMainTable()
        placeBachelors(currentBId, e.target.value, 'abst-name')
    })
    document.querySelector('#filter-input-prof-program').addEventListener('input', e => {
        const currentBId = sessionStorage.getItem('currentBId')
        cleanMainTable()
        placeBachelors(currentBId, e.target.value, 'prog-ved')
    })
    updbtn().addEventListener('click', e => {
        sessionStorage.clear()
    })
    backBtn().addEventListener('click', e => {
        cleanMainTable()
        document.querySelector('#pracaType').hidden = false
        document.querySelector('#filter').hidden = true
    })
    ids.forEach(idCol => {
        idCol.addEventListener('click', e => {
            sessionStorage.setItem('currentBId', idCol.innerHTML)
            placeBachelors(idCol.innerHTML)

        })
    })

})
