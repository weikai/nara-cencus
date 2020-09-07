import React from 'react';

import ReactDOM from 'react-dom';
import Pagination from "react-js-pagination";

import SearchForm from './SearchForm';
import SearchResultList from './SearchResultList';

const API_ENDPOINT = '/api';


const apiURL = (api,params,searchTerm='') => {
  let query=[];
  for (const key of Object.keys(params)) {
    if (params[key] !== "") {
      query.push(key + '=' + params[key])
    }
  }  
  searchTerm = searchTerm? '/' + searchTerm :'';
  query = query.length? '?' + query.join('&'): '';
  return API_ENDPOINT + '/' + api + searchTerm + query;
}
class Search extends React.Component {
  constructor() {
    super()
    this.state = {
      searchTerm: '',
      results: [],
      page: 1,
      total: 0,
      size: 25,
      formSelectOptions: {
        'states': [],
        'counties': [],
        'cities': []
      },
      formSelectedOptions: {
        'selectedState': { 'value': '', 'label': 'All' },
        'selectedCounty': { 'value': '', 'label': 'All' },
        'selectedCity': { 'value': '', 'label': 'All' },
      },
      ed:'',

    }

  }

  componentDidMount() {
    this.getFormSelectOptions();
    //this.setState({formSelectedOptions:{...this.formSelectedOptions,selectedState:'MD'}});

  }


  getFormSelectOptions = (formSelectedOptions = this.state.formSelectedOptions) => {
    const { selectedState, selectedCounty, selectedCity } = formSelectedOptions;

    fetch(`${API_ENDPOINT}/location?state=${selectedState.value}&county=${selectedCounty.value}&city=${selectedCity.value}`)
      .then(data => data.json())
      .then((data) => {
        this.setState({ formSelectOptions: this.convert_location_data(data) });
      }).catch(error => {
        console.log(error);
      });
  }


  onSubmit = (e) => {
    e.preventDefault();
    //console.log(this.state.formSelectedOptions);
    const { selectedState, selectedCounty, selectedCity } = this.state.formSelectedOptions;
    let tmpSearchTerm = e.target.searchterm.value;
    console.log('etarget', e.target.ed.value);
    let edNumber = e.target.ed.value;
    
    //fetch(`${API_ENDPOINT}/search/${encodeURIComponent(tmpSearchTerm)}?size=${this.state.size}&state=${selectedState.value}&county=${selectedCounty.value}&city=${selectedCity.value}`)
    fetch(apiURL('search',{
      size:this.state.size,
      state:selectedState.value,
      county:selectedCounty.value,
      city:selectedCity.value,
      ed:edNumber,
    },tmpSearchTerm))
      .then(data => data.json())
      .then(({ results, page, total,ed }) =>
        this.setState({ results: [...results], page, total, searchTerm: tmpSearchTerm,ed:edNumber })
      ).catch(error => {
        console.log(error);
      });



  }

  onPaginationChange = (page) => {
    console.log(this.state);
    const {searchTerm, formSelectedOptions, ed,size  } = this.state;

    //fetch(`${API_ENDPOINT}/search/${encodeURIComponent(this.state.searchTerm)}?size=${this.state.size}&page=${pageNumber}`)
    fetch(apiURL('search',{
      size,
      state:formSelectedOptions.selectedState.value,
      county:formSelectedOptions.selectedCounty.value,
      city:formSelectedOptions.selectedCity.value,
      ed,
      searchTerm,
      page
    },searchTerm))
      .then(data => data.json())
      .then(({ results, page, total }) =>
        this.setState({ results: [...results], page, total })
      ).catch(error => {
        console.log(error);
      });
  }


  onSelectOptionChange = (stateSelected, value) => {
    let formSelectedOptions = {};
    switch (stateSelected) {
      case 'state':
        formSelectedOptions = { ...this.state.formSelectedOptions, selectedState: value };
        break;
      case 'county':
        formSelectedOptions = { ...this.state.formSelectedOptions, selectedCounty: value };
        break;
      case 'city':
        formSelectedOptions = { ...this.state.formSelectedOptions, selectedCity: value };
        break;
    }
    this.getFormSelectOptions(formSelectedOptions);
    this.setState({ formSelectedOptions });


  }


  convert_location_data = (data) => {
    let state = [];
    let county = [];
    let city = [];
    if (data.state) {
      state = data.state.map((item) => {
        return { value: item.abbr, label: item.name };
      })
    }

    if (data.county) {
      county = data.county.map((item) => {
        return { value: item, label: item };
      })
    }

    if (data.city) {
      city = data.city.map((item) => {
        return { value: item, label: item };
      })
    }

    state.unshift({ value: '', label: 'All' });
    county.unshift({ value: '', label: 'All' });
    city.unshift({ value: '', label: 'All' });
    data = { states: state, counties: county, cities: city };
    return data;
  }

  render() {
    //console.log(apiURL('search',{ 'test': 'va1','test2': 'val2', 'test3': '' },'alabama'));
    console.log(this.state);
    return (
      <div>
        <SearchForm
          parent={this}
          selectedOptions={this.state.formSelectedOptions}


        />
        <SearchResultList list={this.state.results} />
        {
          parseInt(this.state.total) > parseInt(this.state.size) &&
          <Pagination
            itemClass="page-item"
            linkClass="page-link"
            activePage={parseInt(this.state.page)}
            itemsCountPerPage={parseInt(this.state.size)}
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

