import React, { useState, useEffect } from 'react';

const QueueDisplay = ({ apiEndpoint = '/api/queue.php' }) => {
  const [queue, setQueue] = useState([]);
  const [loading, setLoading] = useState(true);

  const fetchQueue = async () => {
    try {
      const response = await fetch(apiEndpoint);
      const data = await response.json();
      setQueue(data);
      setLoading(false);
    } catch (error) {
      console.error('Error fetching queue:', error);
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchQueue();
    const interval = setInterval(fetchQueue, 5000); // Refresh every 5 seconds
    return () => clearInterval(interval);
  }, []);

  const getStatusColor = (status) => {
    switch(status) {
      case 'waiting': return '#f59e0b';
      case 'in_room': return '#10b981';
      case 'finished': return '#6b7280';
      default: return '#3b82f6';
    }
  };

  const getStatusIcon = (status) => {
    switch(status) {
      case 'waiting': return '⏳';
      case 'in_room': return '🏥';
      case 'finished': return '✅';
      default: return '📋';
    }
  };

  if (loading) {
    return <div style={{ textAlign: 'center', padding: '20px' }}>Loading queue...</div>;
  }

  return (
    <div style={{
      backgroundColor: 'white',
      borderRadius: '12px',
      padding: '20px',
      boxShadow: '0 4px 6px rgba(0,0,0,0.1)'
    }}>
      <h2 style={{ marginTop: 0, marginBottom: '20px', fontSize: '24px', color: '#1f2937' }}>
        📋 Current Queue
      </h2>
      
      {queue.length === 0 ? (
        <p style={{ textAlign: 'center', color: '#6b7280', padding: '40px' }}>
          No one in queue. Be the first to join!
        </p>
      ) : (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
          {queue.map((member, index) => (
            <div key={member.id} style={{
              display: 'flex',
              alignItems: 'center',
              padding: '16px',
              backgroundColor: member.status === 'in_room' ? '#f0fdf4' : '#f9fafb',
              borderRadius: '8px',
              border: `2px solid ${member.status === 'in_room' ? '#10b981' : '#e5e7eb'}`,
              transition: 'all 0.3s'
            }}>
              <div style={{
                width: '40px',
                height: '40px',
                borderRadius: '50%',
                backgroundColor: getStatusColor(member.status),
                color: 'white',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontWeight: 'bold',
                marginRight: '16px'
              }}>
                {member.queue_number}
              </div>
              
              <div style={{ flex: 1 }}>
                <div style={{ fontWeight: '600', fontSize: '16px', marginBottom: '4px' }}>
                  {member.name}
                </div>
                <div style={{ fontSize: '14px', color: '#6b7280' }}>
                  {getStatusIcon(member.status)} {member.status.replace('_', ' ').toUpperCase()}
                  {member.status === 'waiting' && index === 0 && member.status !== 'in_room' && (
                    <span style={{ marginLeft: '8px', color: '#10b981', fontWeight: '500' }}>
                      (Next)
                    </span>
                  )}
                </div>
              </div>
              
              {member.status === 'waiting' && (
                <div style={{ textAlign: 'right' }}>
                  <div style={{ fontSize: '12px', color: '#6b7280' }}>Est. wait</div>
                  <div style={{ fontSize: '14px', fontWeight: '600', color: '#3b82f6' }}>
                    {index * 15} min
                  </div>
                </div>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default QueueDisplay;
