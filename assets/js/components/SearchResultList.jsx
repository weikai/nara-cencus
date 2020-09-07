import React from 'react';
import Select from 'react-select';

const SearchResultList = ({ list }) =>
  <div>    
    {
      list.length > 0 && <ul className="rows record-list">
        {        
          list.map((item, i) =>
            <li className="col-md-10 row-block" key={i}>
              <div className='desc'>
                {item.description}
              </div>
              <a href={item.url}>{item.state_name} >> {item.county} >> ED {item.ed}</a>          
            </li>)
        }
    </ul>
    }
   
    
  </div>
  

export default SearchResultList;