<style>
    .hide {
        display: none !important;
    }

    .spinner {
        height: 30vw;
        width: 30vw;
        margin: 10px;
    }

    .sortable::after {
        content: '';
        position: relative;
        top: 15px;
        left: 5px;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-top: 6px solid white;
        transition: transform 0.3s ease;
    }

    .sortable.down::after {
        transform: rotateY(180deg);
    }

    .open-row {
        text-transform: uppercase;
        align-items: center;
        transition: 0.05s;
        user-select: none;
        cursor: pointer;
    }

    .open-row:hover {
        transform: scale(1.1);
    }

    .rec-name {
        margin: 0;
        width: fit-content;
        transition: 0.1s;
        user-select: none;
        cursor: pointer;
    }

    .rec-name:hover {
        transform: translateX(5px);
        color: #0069d9;
    }
</style>

<div class="container mt-2">
    <div class="row mb-1">
        <div class="col-12 col-md-auto d-flex align-items-end">
            <button id="reset-filters" type="button" class="btn btn-primary w-100">Reset filters</button>
        </div>
        <div class="col-md">
            <label class="mt-1 mb-0" for="year-filter">Filter by year</label>
            <input type="text" id="year-filter" class="form-control" placeholder="Year ">
        </div>
        <div class="col-md">
            <label class="mt-1 mb-0" for="category-filter">Filter by category</label>
            <select id="category-filter" class="form-control">
                <option selected>Category</option>
            </select>
        </div>
        <div class="col-12 col-md-auto d-flex align-items-end">
            <button id="show-all" type="button" class="btn btn-secondary w-100 mt-3">show all</button>
        </div>
    </div>
    <div id="spinner-box" class="d-flex justify-content-center">
        <div class="spinner spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <table id="main-table" class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th id="name" scope="col" onclick="toggleArrow(this)" class="sortable">Name</th>
                <th id="year" scope="col" onclick="toggleArrow(this)" class="sortable">Year</th>
                <th id="category" scope="col" onclick="toggleArrow(this)" class="sortable">Category</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <nav aria-label=" Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <button class="page-link" id="prev" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </button>
            </li>
            <li class="page-item disabled"><a class="page-link">...</a></li>
            <li class="page-item disabled"><a id="pagenum" class="page-link"></a></li>
            <li class="page-item disabled"><a class="page-link">...</a></li>
            <li class="page-item">
                <button class="page-link" id="next" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </button>
            </li>
        </ul>
    </nav>
</div>

<script>
    function toggleArrow(element) {
        element.classList.toggle('down');
    }
</script>
<script type="module" src="/z1/frontend/js/prizes.js"></script>