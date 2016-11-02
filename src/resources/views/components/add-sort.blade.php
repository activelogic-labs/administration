{{--
    Created by: Dalton Gibbs
    Date: 11/1/16
    Time: 1:20 PM
--}}

@if($sortable)
    <div class="add-sort">
        <div class="filter-button new-sort-button">
            <i class="fa fa-plus"></i>
            <span>Add Sort</span>
        </div>
        <form class="add-sort-form">
            <div class="select-sort-label">Sort By</div>
            <div class="select-sort">
                <select name="sortColumn">
                    <option selected disabled>Sort Options</option>
                    @foreach($sortable as $key => $option)
                        <option value="{{ $key }}">{{ $option['title'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sort-input">
                <div class="define-sort-label">Sort Direction</div>
                <div class="define-sort">
                    <select name="sortDirection">
                        <option value="ASC">Ascending</option>
                        <option value="DESC">Descending</option>
                    </select>
                </div>
                <button class="filter-button add-sort-button">Add</button>
            </div>
        </form>
    </div>
@endif