import React from 'react';
import Select from 'react-select';

const SearchForm = ({ parent, searchTerm, selectedOptions}) => {
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
            filterOption={false}  
            value={selectedOptions.selectedState}                       
            onChange={parent.onSelectOptionChange.bind(this,'state')}
            options={parent.state.formSelectOptions.states}
          />

          <Select
            className="form-control"            
            filterOption={false}
            value={selectedOptions.selectedCounty}
            onChange={parent.onSelectOptionChange.bind(this,'county')}
            options={parent.state.formSelectOptions.counties}
          />

          <Select
            className="form-control"            
            filterOption={false}
            value={selectedOptions.selectedCity}
            onChange={parent.onSelectOptionChange.bind(this,'city')}
            options={parent.state.formSelectOptions.cities}
          />

          <input
            type="text"
            className="form-control mr-sm-2"
            title="Enumeration Districts"
            id="searched"
            name="ed"
            value={searchTerm} //can't use parent since it changes value
            placeholder="Enter enumeration district number" />
          
          
        </div>
      </form>
    </React.Fragment>
  );
};



export default SearchForm;