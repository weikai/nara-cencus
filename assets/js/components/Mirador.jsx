import React, { Component } from "react";
import mirador from "mirador";

class Mirador extends Component {
    constructor() {
        super()
        this.myconfig={
            id: 'viewer',
            windows: [{
                manifestId: 'https://iiif.drupalme.net/manifest.json',                
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
    }
    componentDidMount() {
        const { config, plugins } = this.props;
        
        mirador.viewer(this.myconfig);
    }
    render() {
        const { config } = this.props;
        return <div id={this.myconfig.id} />;
    }
}

export default Mirador;