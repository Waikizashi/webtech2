import { showToast, isset } from "/frontend/utils/utils.js";

export class BachelorsService {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    async getBachelors(pId) {
        if (!isset(pId)) {
            console.error('Invalid pracoviste: ' + pId)
            return null
        }
        try {
            var url = `${this.baseUrl}/bachelors?pracoviste=${pId}`;

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            return data
        } catch (error) {
            console.error("DATA: ", error);
            return null;
        }
    }
}