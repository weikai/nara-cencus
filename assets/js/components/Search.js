import React from 'react';
import ReactDOM from 'react-dom';
import Pagination from "react-js-pagination";

const censusSearchUrl = (value, page, limit) =>
  `/api/search/${value}/${limit}/${page}`;

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
        state:''
      }      
    };
  }

  onInitialSearch = (e) => {
    e.preventDefault();
    
    if (this.state.form.searchTerm === '') {
      return;
    }
    this.state.search = {            
        results:[],
        page: 0,
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
    fetch(censusSearchUrl(value, page, this.state.search.limit))
      .then(response => response.json())      
      .then(result => this.setState({
        search: result
      }));      

  

  render() {

    return (
      <div className="page">
        <div className="generalsearch">
          <form type="submit" onSubmit={this.onInitialSearch}>
            <input type="text" title="Search" onChange={this.editSearchTerm} value={this.state.form.searchTerm} />
            <button type="submit">Search</button>
           

            
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