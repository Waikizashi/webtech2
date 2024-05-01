import { showToast } from "../js/utils/utils.js";

export class NobelService {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    async getCategories() {
        try {
            var url = `${this.baseUrl}/categories`;

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error("Could not fetch prizes: ", error);
            return null;
        }
    }
    async getReceiverById(recId) {
        try {
            var url = `${this.baseUrl}/nobel-receiver`;
            if (recId) {
                url += `?recid=${recId}`;
            } else {
                console.error("Param __RECEVIER NAME__ missing: ", url);
                return null
            }

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error("Could not fetch prizes: ", error);
            return null;
        }
    }

    async getPrize(id) {
        try {
            var url = `${this.baseUrl}/prize`;

            if (id && id > 0) {
                url += `?id=${id}`;
            } else {
                console.error("Param __ID__ missing: ", url);
                return null
            }

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error("Could not fetch prizes: ", error);
            return null;
        }
    }

    async getPrizes(page, perpage, filter) {
        try {
            let paramSlice = '?'
            let url = `${this.baseUrl}/prizes`;

            if ((page && page) && (page > 0)) {
                url += `${paramSlice}page=${page}`;
                paramSlice = '&'
            }
            if ((perpage && perpage) && (perpage > 0)) {
                url += `${paramSlice}perpage=${perpage}`;
                paramSlice = '&'
            }
            if (filter && filter || filter.filter || filter.value) {
                url += `${paramSlice}${filter.filter}filter=${filter.value}`;
                paramSlice = '&'
            }

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error("Could not fetch prizes: ", error);
            return null;
        }
    }
    async getPrizesSortedByYear(page, perpage, sortMethod, filter) {
        try {
            let paramSlice = '?'
            var url = `${this.baseUrl}/prizes`;

            if ((page && page) && (page > 0)) {
                url += `${paramSlice}page=${page}`;
                paramSlice = '&'
            }
            if ((perpage && perpage) && (perpage > 0)) {
                url += `${paramSlice}perpage=${perpage}`;
                paramSlice = '&'
            }
            if ((filter && filter) && filter.filter && filter.value) {
                url += `${paramSlice}${filter.filter}filter=${filter.value}`;
                paramSlice = '&'
            }

            url += `${paramSlice}sortbyyear=${sortMethod}`;

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error("Could not fetch prizes: ", error);
            return null;
        }
    }

    async getPrizesSortedByName(page, perpage, sortMethod, filter) {
        try {
            let paramSlice = '?'
            var url = `${this.baseUrl}/prizes`;

            if ((page && page) && (page > 0)) {
                url += `${paramSlice}page=${page}`;
                paramSlice = '&'
            }
            if ((perpage && perpage) && (perpage > 0)) {
                url += `${paramSlice}perpage=${perpage}`;
                paramSlice = '&'
            }
            if ((filter && filter) && filter.filter && filter.value) {
                url += `${paramSlice}${filter.filter}filter=${filter.value}`;
                paramSlice = '&'
            }

            url += `${paramSlice}sortbyname=${sortMethod}`;

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error("Could not fetch prizes: ", error);
            return null;
        }
    }

    async getPrizesSortedByCategory(page, perpage, sortMethod, filter) {
        try {
            let paramSlice = '?'
            var url = `${this.baseUrl}/prizes`;

            if ((page && page) && (page > 0)) {
                url += `${paramSlice}page=${page}`;
                paramSlice = '&'
            }
            if ((perpage && perpage) && (perpage > 0)) {
                url += `${paramSlice}perpage=${perpage}`;
                paramSlice = '&'
            }
            if ((filter && filter) && filter.filter && filter.value) {
                url += `${paramSlice}${filter.filter}filter=${filter.value}`;
                paramSlice = '&'
            }

            url += `${paramSlice}sortbycategory=${sortMethod}`;

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error("Could not fetch prizes: ", error);
            return null;
        }
    }

    async deletePrize(prizeId) {
        try {
            console.log(prizeId)
            var url = `${this.baseUrl}/prize`;

            if (prizeId && prizeId > 0) {
            } else {
                console.error("Param __ID__ missing: ", url);
                return null
            }

            const response = await fetch(url, {
                method: "DELETE",
                body: JSON.stringify({ id: prizeId })
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            showToast('Successfuly deleted', 'Prize successfuly deleted', 'success')
            return data;
        } catch (error) {
            console.error("Could not fetch prizes: ", error);
            return null;
        }
    }

    async updatePrize(prizeData) {
        try {
            var url = `${this.baseUrl}/prize`;

            const response = await fetch(url, {
                method: "PUT",
                body: JSON.stringify(prizeData),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            console.log(data)
            showToast('Successfuly updated', 'Receiver and prize successfuly updated', 'success')
            return data;
        } catch (error) {
            console.error("Could not fetch prizes: ", error);
            return null;
        }
    }

    async createPrize(prizeData) {
        try {
            const response = await fetch(`${this.baseUrl}/prize`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8"',
                },
                body: JSON.stringify(prizeData),
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            //const data = await response.json();
            showToast('Successfuly added', 'New receiver and new prize successfuly added', 'success')
        } catch (error) {
            console.error("Could not create prize: ", error);
            return null;
        }
    }
}