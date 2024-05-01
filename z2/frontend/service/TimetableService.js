import { showToast, isset } from "/frontend/utils/utils.js";

export class TimetableService {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    async getTimetable() {
        try {
            var url = `${this.baseUrl}/timetable`;

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const rowData = await response.json();
            return rowData
        } catch (error) {
            console.error("DATA: ", error);
            showToast('ERROR', `${error}: `, 'danger');
            return null;
        }
    }
    async fetchTimetable() {
        try {
            var url = `${this.baseUrl}/timetable?fetch=yes`;

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            showToast('Successfuly fetched', 'Records successfuly fetched', 'success')
            const rowData = await response.json();
            return rowData
        } catch (error) {
            console.error("DATA: ", error);
            showToast('ERROR', `${error}: `, 'danger');
            return null;
        }
    }
    async deleteTimetableRecord(recordId) {
        try {

            if (recordId && recordId > 0) {
            } else {
                console.error("Param __ID__ missing: ", url);
                showToast("Param ID missing: ", 'danger');
                return null
            }
            var url = `${this.baseUrl}/timetable?id=${recordId}`;

            const response = await fetch(url, {
                method: "DELETE",
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            showToast('Successfuly deleted', 'Record successfuly deleted', 'success')

        } catch (error) {
            console.error("Could not fetch prizes: ", error);
            showToast('ERROR', `${error}: `, 'danger');
        }
    }
    async deleteTimetable() {
        try {
            var url = `${this.baseUrl}/timetable?all=true`;

            const response = await fetch(url, {
                method: "DELETE",
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            showToast('Successfuly deleted', 'Record successfuly deleted', 'success')
            // const rowData = await response.json();
            // return rowData;
        } catch (error) {
            console.error("Could not fetch prizes: ", error);
            showToast('ERROR', `${error}: `, 'danger');
            return null;
        }
    }

    async updateTimetable(timetableData) {
        try {
            var url = `${this.baseUrl}/timetable`;

            const response = await fetch(url, {
                method: "PUT",
                body: JSON.stringify(timetableData),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            showToast('Successfuly updated', 'Record successfuly updated', 'success')
            const rowData = await response.json();
            return rowData;
        } catch (error) {
            console.error("Could not fetch timetable: ", error);
            showToast('ERROR', `${error}: `, 'danger');
            return null;
        }
    }

    async createTimetableRecord(timetableRecordData) {
        try {
            const response = await fetch(`${this.baseUrl}/timetable`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8"',
                },
                body: JSON.stringify(timetableRecordData),
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            showToast('Successfuly added', 'New record successfuly added', 'success')
            const rowData = await response.json();
            return rowData;
        } catch (error) {
            console.error("Could not create record: ", error);
            showToast('ERROR', `${error}: `, 'danger');
            return null;
        }
    }
}