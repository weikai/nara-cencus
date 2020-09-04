import React from 'react';
import Select from 'react-select';

const SearchResultList = ({ list }) =>
  <div className="list">
    {
      list.map((item, i) =>
        <div className="list-row" key={i}>
          <a href={item.url}>{item.statename} >> {item.countyname} >> {item.ed}</a>
        </div>
      )
    }
  </div>

export default SearchResultList;