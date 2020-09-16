import React from 'react';
import ReactDOM from 'react-dom';
import Pagination from "react-js-pagination";
import PropTypes from 'prop-types';


import SearchForm from './SearchForm';
import SearchResultList from './SearchResultList';
import BootStrapModal from './BootStrapModal';
import Button from 'react-bootstrap/Button';



const API_ENDPOINT = '/api';



class Search extends React.Component {
  constructor(props) {
    super(props)
    
    this.urlParams = new URLSearchParams(this.props.location.search);
    this.state = {
      searchTerm: this.getSearchTermFromUrl(),      
      results: [],
      page:this.urlParams.get('page')?this.urlParams.get('page'):1,
      total: 0,
      size: this.urlParams.get('size')?this.urlParams.get('size'):25,
      formSelectOptions: {
        'states': [],
        'counties': [],
        'cities': []
      },
      formSelectedOptions: {
        'selectedState': { 'value': this.urlParams.get('state'), 'label': this.urlParams.get('state')?this.urlParams.get('state'):'All' },
        'selectedCounty': { 'value': this.urlParams.get('county'), 'label': this.urlParams.get('county')?this.urlParams.get('county'):'All' },
        'selectedCity': { 'value': this.urlParams.get('city'), 'label': this.urlParams.get('city')?this.urlParams.get('city'):'All' },
      },
      ed:this.urlParams.get('ed'),
      showModal:false,
      manifest:''      
      
    }



  }
  
  componentDidUpdate(){
    console.log('component update');
    this.getQueryPath({
      
    });
  }

  componentDidMount() {

    console.log('componentDidMount');
    console.log(this.props);
    console.log('urlParams');
    console.log(this.urlParams);
    
    this.getFormSelectOptions();   
  
    const {selectedState, selectedScounty, selectedCity}  = this.state.formSelectedOptions;
    //fetch data base url URL query
    if(! this.isEmptyform()){      
      this.fetchSearchResults({
        size:this.state.size,
        page:this.state.page,
        state:this.state.formSelectedOptions.selectedState.value,
        county:this.state.formSelectedOptions.selectedCounty.value,
        city:this.state.formSelectedOptions.selectedCity.value,
        ed:this.state.ed,
        searchTerm: this.state.searchTerm
      });      

      
      if( this.urlParams.has('viewer')){                
        //this.onOpenModal(this.urlParams.get('state'),this.urlParams.get('ed'));
      }
    }
    console.log('calling did mount',this.getQueryPath());
  }

  getApiUrl = (api,params) => {
    return API_ENDPOINT + '/' + api + this.getQueryPath(params);
  }
  getQueryPath = (params = this.urlParams) => {
    let query=[];
    let searchTerm='';
  
    //convert URLSearchParam object value to query string
    if( typeof params === 'object' && params.constructor.name === 'URLSearchParams'){    
      for(var pair of params.entries()) {
        query.push(pair[0]+ '='+ pair[1]);       
     }
    }
    else{
      for (const key of Object.keys(params)) {    
        if (params[key] !== "" && params[key] !== null) {            
          if(key === 'searchTerm'){
            searchTerm = '/' + params[key];
          }
          else{
            query.push(key + '=' + params[key])
          }
        }
      }    
    }
    query = query.length? '?' + query.join('&'): '';
    return searchTerm + query;
  }

  //Open viewer modal
  onOpenModal = (state,ed) => {    
    this.setState({ showModal: true, manifest: `/api/manifest?state=${state}&ed=${ed}` });  
    console.log('calling modal',this.getQueryPath());    
    this.updateQueryUrl({viewer:'on', ed,state});
    console.log('after calling modal',this.getQueryPath());
  }
  
  //Close viewer modal
  onCloseModal = () => {
    this.setState({ showModal: false });
    console.log('close modale',this.getQueryPath());
    this.updateQueryUrl({viewer:''});
  }
  //check to see if form is empty
  isEmptyform = () =>{
    const {selectedState, selectedCounty, selectedCity}  = this.state.formSelectedOptions;
    if(this.state.searchTerm||this.state.ed||selectedState.value||selectedCounty||selectedCity){
      console.log('no empty');      
      return false;
    }
    else{
      console.log('empty');
      return true;
    }
  }

 
  getFormSelectOptions = (formSelectedOptions = this.state.formSelectedOptions) => {
    const { selectedState, selectedCounty, selectedCity } = formSelectedOptions;
         
    fetch(this.getApiUrl('location',{
      state:selectedState.value ? selectedState.value : '',
      county:selectedCounty.value ? selectedCounty.value : '',
      city:selectedCity.value ? selectedCity.value : '',
    }))
      .then(data => data.json())
      .then((data) => {
        this.setState(this.processLocationData(data));
      }).catch(error => {
        console.log(error);
      });
  }

  getSearchTermFromUrl(){
    return this.props.location.pathname.replace(/^\/search\/?/,'');
  }

  onSubmit = (e) => {
    e.preventDefault();
    //window.location.hash = 'test';
    
    const { selectedState, selectedCounty, selectedCity } = this.state.formSelectedOptions;
    let tmpSearchTerm = e.target.searchterm.value;
    
    let edNumber = e.target.ed.value;
    let query = {
      size:this.state.size,
      state:selectedState.value,
      county:selectedCounty.value,
      city:selectedCity.value,
      ed:edNumber,
      searchTerm: tmpSearchTerm
    };
    this.fetchSearchResults(query);  
    
    this.updateQueryUrl(query);
  }  
  onFormReset = (e) =>{
    //this.urlParams = new URLSearchParams(window.location.search);
    // change url base on form value
    window.history.pushState(null, 'reset');
    
    let emptyStates = {
      searchTerm: '',      
      results: [],
      page:1,
      total: 0,
      size: 25,      
      ed:'',
      formSelectedOptions: {
        'selectedState': { 'value':'', 'label':'All' },
        'selectedCounty': { 'value':'', 'label':'All' },
        'selectedCity': { 'value':'', 'label':'All' },
      }      
    };
    
    
    
    this.setState(emptyStates);    
    this.getFormSelectOptions(emptyStates.formSelectedOptions); 
    this.updateQueryUrl(null,"reset");
    console.log('query path after reset url',this.getQueryPath());
    
  }
  
  updateQueryUrl = (query, opt="update") =>{       
    if(opt === "update" || opt === "reset"){
      if(opt === 'reset'){
        this.setState({searchTerm:''});
      }
      for(var pair of this.urlParams.entries()) {        
          console.log('delete param ' + pair[0]);
          this.urlParams.delete(pair[0]);        
      }
    }
    
    if(query){      
      
      for (const [key, value] of Object.entries(query) ) {
        console.log(key,value);
        if(key === 'searchTerm' && value){
          this.state.searchTerm = value;
        }
        else if(value){                    
          this.urlParams.set(key,value);
        }
      }
    }    
    console.log(this.getQueryPath(this.urlParams));
    
    let searchTerm = this.state.searchTerm ? '/' + this.state.searchTerm:''
    window.history.pushState(null, null, '/search' + searchTerm + this.getQueryPath(this.urlParams));    

    console.log('/search' + searchTerm + this.getQueryPath(this.urlParams));
    console.log('***end');
  }  
  
  //function to fetch data
  fetchSearchResults = (query) => {
    this.runSearch = true;
    fetch(this.getApiUrl('search',query))
    .then(data => data.json())
    .then(({ results, page, total }) =>
      this.setState({ results: [...results], page, total, searchTerm: query.searchTerm,ed:query.ed })
    ).catch(error => {
      console.log(error);      
    });      
  }

  

  onPaginationChange = (page) => {    
    const {searchTerm, formSelectedOptions, ed,size  } = this.state;
    
    fetch(this.getApiUrl('search',{
      size,
      state:formSelectedOptions.selectedState.value,
      county:formSelectedOptions.selectedCounty.value,
      city:formSelectedOptions.selectedCity.value,
      ed,
      searchTerm,
      page
    }))
      .then(data => data.json())
      .then(({ results, page, total }) =>
        this.setState({ results: [...results], page, total })
      )
      .then(()=>{     
          
        this.updateQueryUrl({
          searchTerm:this.state.searchTerm,
          state:this.state.formSelectedOptions.selectedState.value,        
          county:this.state.formSelectedOptions.selectedCounty.value,
          city:this.state.formSelectedOptions.selectedCity.value,
          page:this.state.page,
          size:this.state.size
        })
        /*window.history.pushState(null, null, '/search' + this.getQueryPath({
        searchTerm:this.state.searchTerm,
        state:this.state.formSelectedOptions.selectedState.value,        
        county:this.state.formSelectedOptions.selectedCounty.value,
        city:this.state.formSelectedOptions.selectedCity.value,
        page:this.state.page,
        size:this.state.size
      }))
      */
    })
      .catch(error => {
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


  processLocationData = (data) => {
    let state = [];
    let county = [];
    let city = [];
    let formOptions = {};
    
    const {selectedState, selectedCounty, selectedcity} = this.state.formSelectedOptions;
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
    
    let formSelectedOptions = {};
    
    
    if(! selectedState.label && selectedState.value){
      formSelectedOptions.selectedState = {
        'value': selectedState.value,
        'label': state.map( item=>{return item.value.toLowerCase() === selectedState.value.toLowerCase() ? item.label: ''}).join('')
      }
    }    
    
    data = {
      formSelectOptions:data,
      formSelectedOptions: {...this.state.formSelectedOptions, ...formSelectedOptions}
    };
    console.log(data);
    return data;
    //return data;
  }

  render() {
    
    return (
      <div>        
        <SearchForm
          parent={this}          
          selectedOptions={this.state.formSelectedOptions}
        />
                  
        <BootStrapModal
          parent={this}
          showModal={this.state.showModal}
        />

        <SearchResultList 
          parent={this}
          list={this.state.results} 
          
        />
               
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

Search.contextTypes = {
  //router: React.PropTypes.object
};
export default Search;

