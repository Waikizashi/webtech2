<div class="container my-3">
    <h2>Receiver Information</h2>
    <form>
        <div class="form-group">
            <label for="year">Year</label>
            <input type="text" class="form-control" id="year" placeholder="Year">
        </div>
        <div class="form-group">
            <label for="receiver-full-name">Receiver name</label>
            <input type="text" id="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="receiver-full-name">Receiver surname</label>
            <input type="text" id="surname" class="form-control">
        </div>
        <div class="form-group">
            <label for="receiver-full-name">Receiver organization</label>
            <input type="text" id="organization" class="form-control">
        </div>
        <div class="form-group">
            <label for="sex">Sex</label>
            <select id="sex" class="form-control">
                <option selected>Sex</option>
                <option>M</option>
                <option>F</option>
                <option>A</option>
                <option>Q</option>
                <option>J</option>
                <option>P</option>
                <option>O</option>
                <option>K</option>
                <option>I</option>
                <option>U</option>
                <option>Y</option>
                <option>T</option>
                <option>R</option>
                <option>E</option>
                <option>H</option>
            </select>
        </div>
        <div class="form-group">
            <label for="contribution_sk">Birth</label>
            <input type="text" class="form-control" id="birth" placeholder="birth">
        </div>
        <div class="form-group">
            <label for="contribution_sk">Death</label>
            <input type="text" class="form-control" id="death" placeholder="death">
        </div>
        <div class="form-group">
            <label for="contribution_sk">Contribution (SK)</label>
            <input type="text" class="form-control" id="contribution-sk" placeholder="Contribution in Slovak">
        </div>
        <div class="form-group">
            <label for="contribution_en">Contribution (EN)</label>
            <input type="text" class="form-control" id="contribution-en" placeholder="Contribution in English">
        </div>
        <div class="form-group">
            <label for="category_name">Category</label>
            <input type="text" class="form-control" id="category-name" placeholder="Category">
        </div>
        <div class="form-group">
            <label for="country_name">Country</label>
            <input type="text" class="form-control" id="country-name" placeholder="Country">
        </div>
        <div class="form-group">
            <label for="language_sk">Language (SK)</label>
            <input type="text" class="form-control" id="language-sk" placeholder="Language in Slovak">
        </div>
        <div class="form-group">
            <label for="language_en">Language (EN)</label>
            <input type="text" class="form-control" id="language-en" placeholder="Language in English">
        </div>
        <div class="form-group">
            <label for="genre_sk">Genre (SK)</label>
            <input type="text" class="form-control" id="genre-sk" placeholder="Genre in Slovak">
        </div>
        <div class="form-group">
            <label for="genre_en">Genre (EN)</label>
            <input type="text" class="form-control" id="genre-en" placeholder="Genre in English">
        </div>
        <div class="d-flex justify-content-end">
            <a id="save" hidden type="submit" class="ml-2 btn btn-success" disabled>save</a>
            <a id="edit" hidden type="submit" class="mx-2 btn btn-primary" disabled>edit</a>
            <a id="create" hidden type="submit" class="mx-2 btn btn-primary" disabled>create</a>
            <a id="delete" type="submit" class="mx-2 btn btn-danger" disabled>delete</a>
        </div>
    </form>
</div>
<script type="module" src="/z1/frontend/js/nobelReceiver.js"></script>