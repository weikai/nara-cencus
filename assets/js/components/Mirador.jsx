import React, { Component } from "react";
import mirador from "mirador";

class Mirador extends Component {
    constructor() {
        super();
        
        console.log('module constructor');
        

    }
    componentDidMount() {
        

        const { config, manifest, plugins } = this.props;

        console.log('module didMount');
        console.log(manifest);

        this.myconfig={
            id: config.id,
            windows: [{
                manifestId: manifest,                
                thumbnailNavigationPosition: 'far-bottom',
                allowClose: false,
            }],            
            workspace: {
                type: 'single',
            },
            workspaceControlPanel: {
                enabled: false,
            },

        };

        mirador.viewer(this.myconfig);
    }
    render() {
        console.log('module render');
        
        const { config} = this.props;
        return <div id={config.id} />;
    }
}

export default Mirador;