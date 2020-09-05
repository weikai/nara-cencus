import React from 'react';
import Select from 'react-select';

const SearchForm = ({ parent, searchTerm, selectedState }) => {
  return (
    <React.Fragment>
      <form type="submit" className="" onSubmit={parent.onSubmit}>
        <div className="input-group">
          <input
            type="text"
            className="form-control mr-sm-2"
            title="Search"
            id="searchterm"
            name="searchterm"
            value={searchTerm} //can't use parent since it changes value
            placeholder="Enter search term" />
          <button type="submit" className="btn btn-primary">Search</button>
        </div>
        <div className="input-group">
          <Select
            className="form-control"
            value={parent.state.selectedState}
            onChange={parent.onStateSelectChange}
            options={parent.state.stateSelectOptions}
          />

          <Select
            className="form-control"
            value={parent.state.selectedState}
            onChange={parent.onStateSelectChange}
            options={parent.state.stateSelectOptions}
          />
        </div>
      </form>
    </React.Fragment>
  );
};



export default SearchForm;