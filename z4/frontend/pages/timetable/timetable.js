import { TimetableService } from "../../service/TimetableService.js";
import { isset, showToast } from "../../utils/utils.js"

const timetableService = new TimetableService('https://node86.webte.fei.stuba.sk/parse-api')
let data = [];
const tbody = () => document.getElementById('data-rows');
const addRow = (rowData) => {

    const tr = document.createElement('tr');
    data.push(rowData)
    Object.entries(rowData).forEach(([key, value]) => {
        if (key !== 'id') {
            const td = document.createElement('td');
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.value = value;
            input.name = key;
            td.appendChild(input);
            tr.appendChild(td);
        }
    });


    const actionsTd = document.createElement('td');
    const deleteBtn = document.createElement('button');
    deleteBtn.dataset.id = rowData.id;
    deleteBtn.textContent = 'Delete';
    deleteBtn.className = 'delete-btn btn btn-danger btn-sm';
    deleteBtn.onclick = () => tr.remove();

    const updateBtn = document.createElement('button');
    updateBtn.dataset.id = rowData.id;
    updateBtn.disabled = true;
    updateBtn.textContent = 'Update';
    updateBtn.className = 'update-btn btn btn-success btn-sm ml-2';
    tr.querySelector('input[name="day"]').addEventListener('input', e => {
        updateBtn.disabled = false;
    })
    tr.querySelector('input[name="type"]').addEventListener('input', e => {
        updateBtn.disabled = false;
    })
    tr.querySelector('input[name="auditorium"]').addEventListener('input', e => {
        updateBtn.disabled = false;
    })
    tr.querySelector('input[name="subject"]').addEventListener('input', e => {
        updateBtn.disabled = false;
    })
    tr.querySelector('input[name="teacher"]').addEventListener('input', e => {
        updateBtn.disabled = false;
    })

    actionsTd.appendChild(deleteBtn);
    actionsTd.appendChild(updateBtn);
    tr.appendChild(actionsTd);

    tbody().appendChild(tr);
};

function placeTimetableInfo(fetchedInfo) {
    fetchedInfo.forEach(schoolEvent => {
        addRow(schoolEvent)
    })
    document.querySelectorAll('.update-btn').forEach(el => {
        el.addEventListener('click', async (e) => {
            const tr = e.target.parentNode.parentNode;
            const dayValue = tr.querySelector('input[name="day"]').value;
            const typeValue = tr.querySelector('input[name="type"]').value;
            const auditoriumValue = tr.querySelector('input[name="auditorium"]').value;
            const subjectValue = tr.querySelector('input[name="subject"]').value;
            const teacherValue = tr.querySelector('input[name="teacher"]').value;
            const newData = {
                day: dayValue,
                type: typeValue,
                auditorium: auditoriumValue,
                subject: subjectValue,
                teacher: teacherValue
            }
            data = data.map(obj => {
                if (obj.id === parseInt(e.target.dataset.id)) {
                    return { ...obj, ...newData };
                }
                return obj;
            });
            if (data) {
                console.log(data)
                await timetableService.updateTimetable(data)
                e.target.disabled = true;
            } else {
                showToast('Missing data', 'Try to reload page and fetch data', 'warn')
            }
        })
    })
    document.querySelectorAll('.delete-btn').forEach(el => {
        el.addEventListener('click', async (e) => {
            const recordId = e.target.dataset.id
            if (recordId) {
                timetableService.deleteTimetableRecord(recordId)
            } else {
                showToast('Missing data', 'Try to reload page and fetch data', 'warn')
            }
        })
    })
    document.querySelector('#drop-btn').addEventListener('click', async (e) => {
        await timetableService.deleteTimetable()
        tbody().innerHTML = ''
    })
    document.querySelector('#fetch-btn').addEventListener('click', async (e) => {
        const timetableInfo = await timetableService.fetchTimetable()
        tbody().innerHTML = ''
        placeTimetableInfo(timetableInfo)
    })
}

function addSchoolEvent(newData) {
    // const tbody = document.getElementById('data-rows');
    const tr = document.createElement('tr');
    Object.entries(newData).forEach(([key, value]) => {
        if (key !== 'id') {
            const td = document.createElement('td');
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.value = value;
            input.name = key;
            td.appendChild(input);
            tr.appendChild(td);
        }
    });


    const actionsTd = document.createElement('td');
    const saveBtn = document.createElement('button');
    saveBtn.textContent = 'Save';
    saveBtn.className = 'delete-btn btn btn-success btn-sm';

    const deleteBtn = document.createElement('button');
    deleteBtn.hidden = true;
    deleteBtn.textContent = 'Delete';
    deleteBtn.className = 'delete-btn btn btn-danger btn-sm';
    deleteBtn.onclick = () => tr.remove();

    const updateBtn = document.createElement('button');
    updateBtn.hidden = true;
    updateBtn.disabled = true;
    updateBtn.textContent = 'Update';
    updateBtn.className = 'update-btn btn btn-success btn-sm ml-2';
    tr.querySelector('input[name="day"]').addEventListener('input', e => {
        updateBtn.disabled = false;
    })
    tr.querySelector('input[name="type"]').addEventListener('input', e => {
        updateBtn.disabled = false;
    })
    tr.querySelector('input[name="auditorium"]').addEventListener('input', e => {
        updateBtn.disabled = false;
    })
    tr.querySelector('input[name="subject"]').addEventListener('input', e => {
        updateBtn.disabled = false;
    })
    tr.querySelector('input[name="teacher"]').addEventListener('input', e => {
        updateBtn.disabled = false;
    })

    actionsTd.appendChild(saveBtn);
    actionsTd.appendChild(deleteBtn);
    actionsTd.appendChild(updateBtn);
    tr.appendChild(actionsTd);

    tbody().appendChild(tr);

    saveBtn.addEventListener('click', async e => {
        let valid = false;
        const dayValue = tr.querySelector('input[name="day"]').value;
        const typeValue = tr.querySelector('input[name="type"]').value;
        const auditoriumValue = tr.querySelector('input[name="auditorium"]').value;
        const subjectValue = tr.querySelector('input[name="subject"]').value;
        const teacherValue = tr.querySelector('input[name="teacher"]').value;
        isset(valid) &&
            isset(dayValue) &&
            isset(typeValue) &&
            isset(auditoriumValue) &&
            isset(subjectValue) &&
            isset(teacherValue) ? valid = true : valid = false
        if (valid) {
            timetableService.createTimetableRecord({
                day: dayValue,
                type: typeValue,
                auditorium: auditoriumValue,
                subject: subjectValue,
                teacher: teacherValue
            }).then(rowData => {
                console.log(rowData)
                deleteBtn.dataset.id = rowData.id;
                updateBtn.dataset.id = rowData.id;
                saveBtn.hidden = true;
                deleteBtn.hidden = false;
                updateBtn.hidden = false;
                data.push(rowData)
            })

        } else {
            showToast('Check fields', 'check timetable event data', 'danger')
            return null;
        }
    })
}



window.addEventListener('load', async () => {
    document.getElementById('add-row-btn').addEventListener('click', () => {
        addSchoolEvent({ "day": "", "type": "", "auditorium": "", "subject": "", "teacher": "" });
    });
    const timetableInfo = await timetableService.getTimetable();
    placeTimetableInfo(timetableInfo)
})
