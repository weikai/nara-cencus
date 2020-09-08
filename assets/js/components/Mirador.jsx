import ReactDOM from "react-dom";
import React, { Component } from "react";
import mirador from "mirador";

class Mirador extends Component {
  constructor(props) {
    super(props);
    this.miradorInstance = null;
  }
  componentDidMount() {
    const { config, plugins } = this.props;
    this.miradorInstance = mirador.viewer(config, plugins);
    // Example of subscribing to state
    this.miradorInstance.store.subscribe(() => {
      let state = this.miradorInstance.store.getState();
      console.log(state.windows);
    });
    // Hacky example of waiting a specified time to add a window... Don't do this for real
    setTimeout(() => {
      this.miradorInstance.store.dispatch(
        this.miradorInstance.actions.addWindow({
          manifestId: "https://purl.stanford.edu/bk785mr1006/iiif/manifest"
        })
      );
    }, 5000);
  }
  render() {
    const { config } = this.props;
    return <div id={config.id} />;
  }
}

export default Mirador;

