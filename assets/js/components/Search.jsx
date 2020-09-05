import React from 'react';

import ReactDOM from 'react-dom';
import Pagination from "react-js-pagination";

import SearchForm from './SearchForm';
import SearchResultList from './SearchResultList';

const API_ENDPOINT = '/api';
class Search extends React.Component {
  constructor() {
    super()
    this.state = {
      searchTerm: '',
      results: [],
      page: 1,
      total: 0,
      count: 0,
      limit: 25,
      stateSelectOptions:[],          
      selectedStateOption:'',
      countySelectOptions:[],          
      selectedCountyOption:'',
      citySelectOptions:[],          
      selectedCityOption:'',
    }
    this.getStateOptions();
  }

  getStateOptions = (county=null, city=null) =>{
    fetch(`${API_ENDPOINT}/state`)
      .then(data => data.json())
      .then( ({states})=>this.setState({stateSelectOptions: [...states]}));    
  }

  getCountyOptions = (state=null, city=null) =>{
    fetch(`${API_ENDPOINT}/county`)
      .then(data => data.json())
      .then( ({counties})=>this.setState({countySelectOptions: [...counties]}));    
  }

  getCityOptions = (state=null, county=null) =>{
    fetch(`${API_ENDPOINT}/city`)
      .then(data => data.json())
      .then( ({cities})=>this.setState({stateSelectOptions: [...cities]}));    
  }

  onSubmit = (e) => {
    e.preventDefault();
    this.tmpSearchTerm = e.target.searchterm.value;
    fetch(`${API_ENDPOINT}/search/${encodeURIComponent(this.tmpSearchTerm)}?limit=${this.state.limit}&page=${this.state.page}`)
      .then(data => data.json())
      .then(({ results, page, total }) =>
        this.setState({ results: [...results], page, total, searchTerm: this.tmpSearchTerm })
      );



  }

  onPaginationChange = (pageNumber) => {
    fetch(`${API_ENDPOINT}/search/${encodeURIComponent(this.state.searchTerm)}?limit=${this.state.limit}&page=${pageNumber}`)
      .then(data => data.json())
      .then(({ results, page, total }) =>
        this.setState({ results: [...results], page, total })
      );
  }

  
  onStateSelectChange = selectedStateOption =>{    
    console.log(selectedStateOption);
    this.setState({selectedStateOption});
  }
  
 
  render() {
    console.log(API_ENDPOINT);
    return (
      <div>
        <SearchForm 
          parent={this}          
          stateSelectOptions={this.state.stateSelectOptions}          
          selectedState={this.state.selectedState}
        />
        <SearchResultList list={this.state.results} />
        {
          parseInt(this.state.total) > parseInt(this.state.limit) &&
          <Pagination
            itemClass="page-item"
            linkClass="page-link"
            activePage={parseInt(this.state.page)}
            itemsCountPerPage={parseInt(this.state.limit)}
            totalItemsCount={parseInt(this.state.total)}
            pageRangeDisplayed={10}
            onChange={this.onPaginationChange.bind(this)}
          />
        }
      </div>
    );
  }
}


export default Search;

