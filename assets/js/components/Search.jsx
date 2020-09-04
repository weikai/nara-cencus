import React, { createContext } from 'react';
import Select from 'react-select';
import ReactDOM from 'react-dom';
import Pagination from "react-js-pagination";

const census_api = '/api';

const options = [
  { value: 'chocolate', label: 'Chocolate' },
  { value: 'strawberry', label: 'Strawberry' },
  { value: 'vanilla', label: 'Vanilla' }
];



class Search extends React.Component {
  constructor(props) {
    super(props);

    this.state = {      
      search: {
        results:[],
        page: 0,
        total:0,
        count: 0,
        limit: 50,
      },
      form:{
        searchTerm:'',
        stateOptions:[]
      }  
    };
   
  }

  stateOptons = [];

  fetchStateOptons = () => fetch(`${census_api}/state`)
      .then(response => response.json())      
      .then(data => {this.stateOptions = data.states})      
      ;

  

  onInitialSearch = (e) => {
    e.preventDefault();
    
    if (this.state.form.searchTerm === '') {
      return;
    }
    this.state.search = {            
        results:[],
        page: 1,
        total:0,
        count: 0,
        limit: 50      
    };
    this.fetchRecords(this.state.form.searchTerm,1);
  }

  editSearchTerm = (e) =>{
    this.setState({
      form:{searchTerm: e.target.value}
    })
  }

  
  onPaginationChange = (pageNumber) => {    
    this.fetchRecords(this.state.form.searchTerm, pageNumber);    
  }

  

  fetchRecords = (value, page) =>  
    fetch(`${census_api}/search/${value}/${this.state.search.limit}/${page}`)
      .then(response => response.json())      
      .then(result => this.setState({
        search: result
      }));      

  
  

  render() {
    console.log(this.stateOptons);
    this.fetchStateOptons();
    console.log(this.stateOptons);
    return (
      <div className="page">   
        
        {console.log('here')}     
        <div className="generalsearch">
          <form type="submit" onSubmit={this.onInitialSearch}>
            <input type="text" title="Search" onChange={this.editSearchTerm} value={this.state.form.searchTerm} />
            <button type="submit">Search</button>
            <Select options = {this.state.form.stateOptions} />
          </form>
        </div>
        
        <List
          list={this.state.search.results}
        />
       <div className='pagination'>         
         {            
            parseInt(this.state.search.total) > parseInt(this.state.search.limit) && <Pagination
              itemClass="page-item"
              linkClass="page-link"
              activePage={parseInt(this.state.search.page)}
              itemsCountPerPage={parseInt(this.state.search.limit)}
              totalItemsCount={parseInt(this.state.search.total)}
              pageRangeDisplayed={10}
              onChange={this.onPaginationChange.bind(this)}
            />
          }
        </div>
        
      </div>
    );
  }
}

const List = ({ list}) =>
  <div className="list">
    {
      list.map(item =>
        <div className="list-row" key={item.id}>
          <a href={item.url}>{item.state} >> {item.county} >> {item.ed}</a>
        </div>
      )
    }
  </div>
  

export default Search;

