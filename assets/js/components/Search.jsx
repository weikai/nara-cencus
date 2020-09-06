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
      formSelectOptions: {
        'states': [],
        'counties': [],
        'cities': []
      },
      formSelectedOptions:{
        'selectedState': '',
        'selectedCounty': '',
        'selectedCity': '',
      },
    
    }

  }

  componentDidMount() {    
    this.getFormSelectOptions();    
    //this.setState({formSelectedOptions:{...this.formSelectedOptions,selectedState:'MD'}});
    
  }

  getFormSelectOptions = () => {
    fetch(`${API_ENDPOINT}/location`)
      .then(data => data.json())
      .then( (data)=>{
        console.log('here');
        console.log(data);
        this.setState({formSelectOptions:data});
      });      
  }

  getCountyOptions = (state = null, city = null) => {
    fetch(`${API_ENDPOINT}/county`)
      .then(data => data.json())
      .then(({ location }) => this.setState({ countySelectOptions: [...location] }));
  }

  getCityOptions = (state = null, county = null) => {
    fetch(`${API_ENDPOINT}/city`)
      .then(data => data.json())
      .then(({ location }) => this.setState({ citySelectOptions: [...location] }));
  }

  onSubmit = (e) => {
    e.preventDefault();
    let tmpSearchTerm = e.target.searchterm.value;
    fetch(`${API_ENDPOINT}/search/${encodeURIComponent(tmpSearchTerm)}?limit=${this.state.limit}&page=${this.state.page}`)
      .then(data => data.json())
      .then(({ results, page, total }) =>
        this.setState({ results: [...results], page, total, searchTerm: tmpSearchTerm })
      ).catch(error => {
        console.log(error);
      });



  }

  onPaginationChange = (pageNumber) => {
    fetch(`${API_ENDPOINT}/search/${encodeURIComponent(this.state.searchTerm)}?limit=${this.state.limit}&page=${pageNumber}`)
      .then(data => data.json())
      .then(({ results, page, total }) =>
        this.setState({ results: [...results], page, total })
      );
  }


  onSelectOptionChange = (stateSelected,value) => {   
    console.log(stateSelected);    
    switch(stateSelected){
      case 'state':
        this.setState({formSelectedOptions:{...this.formSelectedOptions,selectedState:value}}); 
        break;
      case 'county':
        this.setState({formSelectedOptions:{...this.formSelectedOptions,selectedCounty:value}}); 
        break;
      case 'city':
        this.setState({formSelectedOptions:{...this.formSelectedOptions,selectedCity:value}}); 
        break;
    }
       
    /*
    fetch(`${API_ENDPOINT}/location?state=${selectedOpts.selectedState}&county=${selectedOpts.selectedCounty}&city=${selectedOpts.selectedCity}`)
    .then(data => data.json())
    .then( (data)=>this.setState({formSelectOptions:data, formSelectedOptions:selectedOpts}))
    .catch(error => {
        console.log(error);
    });
    */
    
  }

 


  render() {
    console.log('test2',this.state);
    //console.log(API_ENDPOINT);
    return (
      <div>
        <SearchForm
          parent={this}          
          selectedOptions={this.state.formSelectedOptions} 
          
          
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

