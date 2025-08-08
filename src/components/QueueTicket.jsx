import React from 'react';

const QueueTicket = ({ queueNumber, name, status = 'waiting', estimatedTime = null }) => {
  const statusColors = {
    waiting: { bg: '#fef3c7', border: '#f59e0b', text: '#92400e' },
    in_room: { bg: '#dcfce7', border: '#10b981', text: '#166534' },
    finished: { bg: '#f3f4f6', border: '#9ca3af', text: '#4b5563' }
  };

  const colors = statusColors[status] || statusColors.waiting;

  return (
    <div style={{
      backgroundColor: colors.bg,
      border: `2px solid ${colors.border}`,
      borderRadius: '12px',
      padding: '20px',
      maxWidth: '300px',
      margin: '0 auto',
      textAlign: 'center',
      boxShadow: '0 4px 6px rgba(0,0,0,0.1)'
    }}>
      <div style={{ fontSize: '14px', color: colors.text, marginBottom: '8px' }}>
        QUEUE TICKET
      </div>
      <div style={{ fontSize: '64px', fontWeight: 'bold', color: colors.border, lineHeight: '1' }}>
        #{queueNumber}
      </div>
      <div style={{ fontSize: '18px', color: '#1f2937', marginTop: '12px' }}>
        {name}
      </div>
      <div style={{ fontSize: '14px', color: colors.text, marginTop: '8px' }}>
        Status: {status.replace('_', ' ').toUpperCase()}
      </div>
      {estimatedTime && status === 'waiting' && (
        <div style={{
          marginTop: '16px',
          padding: '8px',
          backgroundColor: 'white',
          borderRadius: '6px'
        }}>
          <div style={{ fontSize: '12px', color: '#6b7280' }}>Estimated wait</div>
          <div style={{ fontSize: '18px', fontWeight: '600', color: '#3b82f6' }}>
            {estimatedTime} minutes
          </div>
        </div>
      )}
    </div>
  );
};

export default QueueTicket;
