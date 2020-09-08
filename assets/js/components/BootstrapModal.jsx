import React from 'react';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';

const BootstrapModal = ({ parent, showModal}) => {  
    return (
      <>
          
        <Modal show={showModal} onHide={parent.onCloseModal}>
          <Modal.Header closeButton>
            <Modal.Title>Modal heading</Modal.Title>
          </Modal.Header>
          <Modal.Body>Woohoo, you're reading this text in a modal!</Modal.Body>
          <Modal.Footer>
            <Button variant="secondary" onClick={parent.onCloseModal}>
              Close
            </Button>            
          </Modal.Footer>
        </Modal>
      </>
    );
  }
  
export default BootstrapModal;