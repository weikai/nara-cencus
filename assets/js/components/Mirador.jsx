import ReactDOM from "react-dom";
import React, { Component } from "react";
//import $ from 'jquery';
//import { findDOMNode } from 'react-dom';

class Mirador extends Component {
    constructor(props) {
        super(props);


    }

    shouldComponentUpdate() {
        return false;
    }

    componentDidMount() {
        /*
        this.myMiradorInstance = Mirador({
            id: 'viewer', //this.refs.viewer,
            layout: "1x1",
            buildPath: "mirador/",
            data: [
              { manifestUri: "https://iiif.lib.harvard.edu/manifests/drs:48309543", location: "Harvard University"}
            ],
            windowObjects: [],
            annotationEndpoint: {
              name:"Local Storage",
              module: "LocalStorageEndpoint" }
          });
          */
   
        

    }

    render() {
        return <div id='viewer' ref='viewer'>Mirador</div>;
    }
}

export default Mirador;

