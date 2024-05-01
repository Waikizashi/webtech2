<style>
    .details-popover {
        background-color: white;
        border: 5px solid #ccc;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
        padding: 10px;
        z-index: 1000;
        cursor: default;
        user-sSelect: none;
        width: fit-content;
        max-width: 600px;
    }

    .table {
        width: auto;
        border-left: 20px solid #dee2e6;
        border-right: 20px solid #dee2e6;
    }

    #sub-table td:nth-child(2) {
        border-right: 2px solid #dee2e6;
        border-left: 2px solid #dee2e6;
        transition: .1s;
        cursor: pointer;
        text-decoration: underline;
    }

    #sub-table td:nth-child(2):hover {
        transform: scale(1.2);
        color: #007bff;
    }
</style>
<button hidden id="back-btn" type="button" class="m-2 btn btn-dark">← Back</button>
<div hidden id="filter" class="mx-2 my-2 row">
    <div class="col">
        <button disabled type="button" class="py-1 mb-1 btn btn-primary">
            <label valid for="filter-input" class=" mx-2 form-label m-0">Name | Abstract filter</label>
        </button>
        <input id="filter-input" class="filter" style="max-width: 550px;">
    </div>
    <div class="col">
        <button disabled type="button" class="py-1 mb-1 btn btn-primary">
            <label for="filter-input-prof-program" class=" mx-2 form-label m-0"> veduci | program filter</label>
        </button>
        <input valid id="filter-input-prof-program" class="filter" style="max-width: 550px;">
    </div>
</div>


<div class="table-responsive">
    <table hidden id="main-table" class="table">
        <thead>
            <tr>
                <th scope="col">Typ</th>
                <th scope="col">Názov témy</th>
                <th scope="col">Vedúci práce</th>
                <th scope="col">Garantujúce pracovisko</th>
                <th scope="col">Program</th>
                <th scope="col">Zameranie</th>
                <th scope="col">Určené pre</th>
                <th scope="col">Obsadené/Max</th>
                <th scope="col">Riešitelia</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <table id="sub-table" class="table m-2">
        <thead>
            <tr>
                <th>Pracovisko</th>
                <th>Kód</th>
            </tr>
        </thead>
        <tbody style="max-width: 800px !important;">
            <tr>
                <td>Ústav automobilovej mechatroniky</td>
                <td>642</td>
            </tr>
            <tr>
                <td>Ústav elektroenergetiky a aplikovanej elektrotechniky</td>
                <td>548</td>
            </tr>
            <tr>
                <td>Ústav elektroniky a fotoniky</td>
                <td>549</td>
            </tr>
            <tr>
                <td>Ústav elektrotechniky</td>
                <td>550</td>
            </tr>
            <tr>
                <td>Ústav informatiky a matematiky</td>
                <td>816</td>
            </tr>
            <tr>
                <td>Ústav jadrového a fyzikálneho inžinierstva</td>
                <td>817</td>
            </tr>
            <tr>
                <td>Ústav multimediálnych informačných a komunikačných technológií</td>
                <td>818</td>
            </tr>
            <tr style="border-bottom: 2px solid #dee2e6;">
                <td>Ústav robotiky a kybernetiky</td>
                <td>356</td>
            </tr>
        </tbody>
        <div id="pracaType">
            <div class="ml-4 form-check">
                <input class="form-check-input" type="radio" name="pracaType" value="BP" id="praca-type0">
                <label class="form-check-label" for="praca-type1">
                    BP
                </label>
            </div>
            <div class="ml-4 form-check">
                <input class="form-check-input" type="radio" name="pracaType" value="DP" id="praca-type1">
                <label class="form-check-label" for="praca-type1">
                    DP
                </label>
            </div>
            <div class="ml-4 form-check">
                <input class="form-check-input" type="radio" name="pracaType" value="DizP" id="praca-type2">
                <label class="form-check-label" for="praca-type2">
                    DizP
                </label>
            </div>
            <div class="ml-4 form-check">
                <input class="form-check-input" type="radio" name="pracaType" value="Non specified" id="praca-type3"
                    checked>
                <label class="form-check-label" for="praca-type3">
                    Non specified
                </label>
            </div>
            <button id="update" type="button" class=" mx-4 my-2 btn btn-secondary">Update Info</button>
        </div>
    </table>
</div>
<script type="module" src="/z2/frontend/pages/bachelors/bachelors.js"></script>