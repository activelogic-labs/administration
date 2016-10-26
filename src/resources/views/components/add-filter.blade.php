{{--
    Created by: Dalton Gibbs
    Date: 10/12/16
    Time: 2:56 PM
--}}

<div class="add-filter">
    <div class="filter-button new-filter-button">
        <i class="fa fa-plus"></i>
        <span>Add Filter</span>
    </div>
    <form class="add-filter-form">
        <div class="select-filter-label">Select a filter</div>
        <div class="select-filter">
            <select name="filterColumn">
                <option selected disabled>Filters</option>
                @foreach($filterable as $key => $option)
                    <option value="{{ $key }}">{{ $option['title'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="define-filter-label">Filter Value</div>
        {{--<input class="define-filter" name="filterValue" placeholder="Filter Value">--}}
        <button class="filter-button add-filter-button">Add</button>
    </form>
</div>