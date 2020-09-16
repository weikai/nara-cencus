import React from 'react';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import Mirador from './Mirador';

const BootStrapModal = ({ parent, showModal }) => {
  return (
    <>

      <Modal
        show={showModal} onHide={parent.onCloseModal}
        dialogClassName="modal-size vw-95 vh-95"
      >
        
        <Modal.Header closeButton>
          {/*
          <Modal.Title id="example-custom-modal-styling-title">
            Mirador Viewer Placeholder        
          </Modal.Title>          

          */}
          
        </Modal.Header>
        
        <Modal.Body>
        <Mirador config={{ id: "viewer" }} manifest={parent.state.manifest} plugins={[]} />
          
        </Modal.Body>
      </Modal>
    </>
  );
}

export default BootStrapModal;