import React from 'react';
import Select from 'react-select';

const SearchForm = ({ parent, selectedOptions}) => {
  return (
    <React.Fragment>
      <form type="submit" className="" onSubmit={parent.onSubmit}>
        <div className="form-row">
          <div className="input-group col-md-12">
            <input
              type="text"
              className="form-control mr-sm-2"
              title="Search"
              id="searchterm"
              name="searchterm"
              defaultValue={parent.state.searchTerm}            
              placeholder="Enter search term" />
            <button type="submit" className="btn btn-primary">Search</button>
          </div>
        </div>
        <div className="form-row cform-row">          
            <div className="from-group col-md-6">
              <label forid='state'>States</label>
              <Select                                  
                id="state"
                value={selectedOptions.selectedState}                       
                onChange={parent.onSelectOptionChange.bind(this,'state')}
                options={parent.state.formSelectOptions.states}
              />
            </div>
            <div className="from-group col-md-6">
              <label forid='county'>County</label>
              <Select                
                id="county"
                filterOption={false}            
                value={selectedOptions.selectedCounty}
                onChange={parent.onSelectOptionChange.bind(this,'county')}
                options={parent.state.formSelectOptions.counties}
              />
            </div>
          </div>        
          <div className="form-row cform-row">          
            <div className="form-group col-md-6">
              <label forid="city">City</label>
              <Select                
                id="city"      
                filterOption={false}
                value={selectedOptions.selectedCity}
                onChange={parent.onSelectOptionChange.bind(this,'city')}
                options={parent.state.formSelectOptions.cities}
              />
            </div>
            <div className="form-group col-md-6">
              <label forid='ed'>Enumeration Districts</label>
              <input
                className="form-control" 
                type="text"                
                id='ed'
                title="Enumeration Districts"                
                name="ed"    
                defaultValue={parent.state.ed}        
                placeholder="Enter enumeration district number" />
            </div>
          </div>
        <div className="form-row cform-row">
          <div className="form-group  input-cgroup reset-group">
            <button 
              type="reset" 
              onClick={parent.onFormReset}
              className="btn btn-primary">
                Reset
            </button>
          </div>
        </div>
      </form>
    </React.Fragment>
  );
};



export default SearchForm;