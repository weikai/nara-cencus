import React from 'react';
import Select from 'react-select';

const SearchForm = ({ onSubmit, searchTerm, stateSelectOptions, onStateSelectChange,selectedState }) => {
  return (
    <React.Fragment>
      <form type="submit" className="" onSubmit={onSubmit}>
        <div className="input-group">
          <input
            type="text"
            className="form-control mr-sm-2"
            title="Search"
            id="searchterm"
            name="searchterm"
            value={searchTerm}
            placeholder="Enter search term" />
          <button type="submit" className="btn btn-primary">Search</button>
        </div>
        <div className="input-group">
          <Select
            className="form-control"
            value={selectedState}
            onChange={onStateSelectChange}
            options={stateSelectOptions}
          />
        </div>
      </form>
    </React.Fragment>
  );
};



export default SearchForm;