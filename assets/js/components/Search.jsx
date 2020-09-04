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
    }
    this.getStateOptions();
  }

  getStateOptions = (county=null, city=null) =>{
    fetch(`${API_ENDPOINT}/state`)
      .then(data => data.json())
      .then( ({states})=>this.setState({stateSelectOptions: [...states]}));    
  }

  onSubmit = (e) => {
    e.preventDefault();
    this.tmpSearchTerm = e.target.searchterm.value;
    fetch(`${API_ENDPOINT}/search/${this.tmpSearchTerm}/${this.state.limit}/${this.state.page}`)
      .then(data => data.json())
      .then(({ results, page, total }) =>
        this.setState({ results: [...results], page, total, searchTerm: this.tmpSearchTerm })
      );



  }

  onPaginationChange = (pageNumber) => {
    fetch(`${API_ENDPOINT}/search/${this.state.searchTerm}/${this.state.limit}/${pageNumber}`)
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
          
          onSubmit={this.onSubmit} 
          stateSelectOptions={this.state.stateSelectOptions}
          onStateSelectChange={this.onStateSelectChange}
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

