import React from 'react';
import Select from 'react-select';

const SearchResultList = ({ parent, list }) =>
  <div>    
    {
      list.length > 0 && <ul className="rows record-list">
        {        
          list.map((item, i) =>
            <li className="row-block" key={i}>
              <div className='desc'>
                {item.description}
              </div>
              <a href="#" onClick={()=>parent.onOpenModal(item.state_abbr,item.ed)}>{item.state_name} >> {item.county} >> ED {item.ed}</a>          
            </li>)
        }
    </ul>
    }
   
    
  </div>
  

export default SearchResultList;