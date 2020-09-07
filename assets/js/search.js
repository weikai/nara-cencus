import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router } from 'react-router-dom';

import Search from './components/Search'
import '../css/search.css';

ReactDOM.render(
    <Router>
        <Search />
        
    </Router>
    ,document.getElementById('root')
);
