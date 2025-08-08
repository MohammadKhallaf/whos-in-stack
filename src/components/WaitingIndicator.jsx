import React from 'react';

const WaitingIndicator = ({ count = 0 }) => {
  return (
    <div style={{
      backgroundColor: 'white',
      borderRadius: '12px',
      padding: '20px',
      textAlign: 'center',
      boxShadow: '0 4px 6px rgba(0,0,0,0.1)'
    }}>
      <div style={{ fontSize: '48px', marginBottom: '12px' }}>
        {count > 0 ? '⏳' : '✨'}
      </div>
      <div style={{ fontSize: '14px', color: '#6b7280', marginBottom: '8px' }}>
        People Waiting
      </div>
      <div style={{ fontSize: '48px', fontWeight: 'bold', color: count > 0 ? '#f59e0b' : '#10b981' }}>
        {count}
      </div>
      {count === 0 && (
        <div style={{ fontSize: '14px', color: '#10b981', marginTop: '8px' }}>
          No wait time!
        </div>
      )}
    </div>
  );
};

export default WaitingIndicator;
