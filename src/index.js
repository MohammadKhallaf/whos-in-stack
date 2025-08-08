import React from 'react';
import ReactDOM from 'react-dom/client';
import QueueDisplay from './components/QueueDisplay';
import RoomStatus from './components/RoomStatus';
import JoinQueueForm from './components/JoinQueueForm';
import QueueTicket from './components/QueueTicket';
import AdminPanel from './components/AdminPanel';
import WaitingIndicator from './components/WaitingIndicator';

// Register components globally for PHP
window.ReactComponents = {
  QueueDisplay,
  RoomStatus,
  JoinQueueForm,
  QueueTicket,
  AdminPanel,
  WaitingIndicator
};

// Mount function for PHP
window.mountReactComponent = (componentName, elementId, props = {}) => {
  const Component = window.ReactComponents[componentName];
  if (Component && document.getElementById(elementId)) {
    const root = ReactDOM.createRoot(document.getElementById(elementId));
    root.render(React.createElement(Component, props));
  }
};

// Auto-mount components
document.addEventListener('DOMContentLoaded', () => {
  const reactMounts = document.querySelectorAll('[data-react-component]');
  
  reactMounts.forEach(mount => {
    const componentName = mount.dataset.reactComponent;
    const props = mount.dataset.reactProps ? JSON.parse(mount.dataset.reactProps) : {};
    const root = ReactDOM.createRoot(mount);
    const Component = window.ReactComponents[componentName];
    
    if (Component) {
      root.render(React.createElement(Component, props));
    }
  });
});
