import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';

const applyUpdateResult = (result) => (prevState) => ({
    results: [...prevState.results, ...result.results],
    page: result.page,
  });
   
  const applySetResult = (result) => (prevState) => ({
    results: result.results,
    page: result.page,
  });
   
  const censusSearchUrl = (value, page) =>
    `/api/search/${value}/100/${page}`;
    //`https://hn.algolia.com/api/v1/search?query=${value}&page=${page}&hitsPerPage=100`;  
   
  class App extends React.Component {
    constructor(props) {
      super(props);
   
      this.state = {
       results: [],
        page: null,
      };
    }
   
    onInitialSearch = (e) => {
      e.preventDefault();
   
      const { value } = this.input;
   
      if (value === '') {
        return;
      }
   
      this.fetchStories(value, 0);
    }
   
    onPaginatedSearch = (e) =>{
      console.log('ksjdkfj')
      console.log(this.input.value);
      console.log(this.input.page)
      this.fetchStories(this.input.value, this.state.page + 1);
    }
   
    fetchStories = (value, page) =>
      fetch(censusSearchUrl(value, page))
        .then(response => response.json())
        .then(result => this.onSetResult(result, page));
   
    onSetResult = (result, page) =>{
      console.log(result);
      console.log(page);
      page === 0
        ? this.setState(applySetResult(result))
        : this.setState(applyUpdateResult(result));
    }
   
    render() {
      
      return (
        <div className="page">
          <div className="interactions">
            <form type="submit" onSubmit={this.onInitialSearch}>
              <input type="text" ref={node => this.input = node} />
              <button type="submit">Search</button>
            </form>
          </div>
   
          <List
            list={this.state.results}
            page={this.state.page}
            onPaginatedSearch={this.onPaginatedSearch}
          />
        </div>
      );
    }
  }
   
  const List = ({ list, page, onPaginatedSearch }) =>    
    <div>
      <div className="list">
        {
          list.map(item => 
          <div className="list-row" key={item.id}>
            <a href={item.url}>{item.state} >> {item.county} >> {item.ed}</a>            
          </div>
          )
        }
      </div>
   
      <div className="interactions">
        {
          page !== null && <div>
            <button
              type="button"
              onClick={onPaginatedSearch}
            >
              More
            </button>

          </div>
        }
      </div>
    </div>
ReactDOM.render(<App />, document.getElementById('root'));