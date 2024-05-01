<style>
    .res-table {
        max-height: 75vh;
        overflow: auto;
    }

    table {
        min-width: 1350px;
    }
</style>

<div class="m-4">
    <div class="d-flex ">
        <button id="fetch-btn" class="m-2 btn btn-info">Fetch timetable</button>
        <button id="drop-btn" class="m-2 btn btn-danger">Drop timetable</button>
    </div>
    <div class="res-table">
        <table class="m-2 table table-bordered">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Type</th>
                    <th>Auditorium</th>
                    <th>Subject</th>
                    <th>Teacher</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="data-rows">
            </tbody>
        </table>
    </div>
    <div class="d-flex">
        <button id="add-row-btn" class="m-2 btn btn-primary">Add New Record</button>
        <!-- <button id="save-btn" class="m-2 btn btn-success">Save changes</button> -->
    </div>
</div>

<script type="module" src="/z2/frontend/pages/timetable/timetable.js"></script>