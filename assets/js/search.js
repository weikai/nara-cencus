import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Switch, Route, Link } from 'react-router-dom';

import 'bootstrap/dist/css/bootstrap.min.css';
import Search from './components/Search'

import '../css/search.css';

ReactDOM.render(
    <Router>
        <Switch>
            <Route path="/" component={Search} />        
        </Switch>
    </Router>
    ,document.getElementById('root')
);
